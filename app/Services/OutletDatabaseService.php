<?php

namespace App\Services;

use App\Models\Outlet;
use App\Models\OutletConfiguration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;
use Exception;

class OutletDatabaseService
{
    /**
     * Test connection to an outlet's database
     */
    public function testConnection(Outlet $outlet, ?array $credentials = null): array
    {
        try {
            $connectionName = 'outlet_test_' . $outlet->OutletID . '_' . time();

            $host = $outlet->IPAddress;
            $database = $credentials['DatabaseName'] ?? '';
            $port = $credentials['DatabasePort'] ?? '1433';
            $username = $credentials['DatabaseUser'] ?? '';
            $password = $credentials['DatabasePassword'] ?? '';

            // Validation
            if (empty($host)) {
                return ['success' => false, 'message' => 'IP Address is required'];
            }
            if (empty($database)) {
                return ['success' => false, 'message' => 'Database Name is required'];
            }
            if (empty($username)) {
                return ['success' => false, 'message' => 'Username is required'];
            }

            Log::info('Testing connection', [
                'host' => $host,
                'port' => $port,
                'database' => $database,
                'username' => $username,
            ]);

            Config::set("database.connections.{$connectionName}", [
                'driver' => 'sqlsrv',
                'host' => $host,
                'port' => $port,
                'database' => $database,
                'username' => $username,
                'password' => $password,
                'charset' => 'utf8',
                'prefix' => '',
                'encrypt' => 'no',
                'trust_server_certificate' => true,
            ]);

            DB::connection($connectionName)->getPdo();
            DB::purge($connectionName);

            return [
                'success' => true,
                'message' => 'Connection successful to ' . $host,
            ];

        } catch (Exception $e) {
            if (isset($connectionName)) {
                DB::purge($connectionName);
            }

            Log::error('Outlet connection test failed', [
                'outlet_id' => $outlet->OutletID,
                'ip' => $outlet->IPAddress,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Execute SQL script on outlet's database (from Configuration)
     */
    public function executeScript(OutletConfiguration $config): array
    {
        $outlet = $config->outlet;

        if (!$outlet) {
            return ['success' => false, 'message' => 'Outlet not found'];
        }

        if (!$outlet->IPAddress) {
            return ['success' => false, 'message' => 'Outlet IP address is not configured'];
        }

        $credentials = [
            'DatabaseName' => $config->DatabaseName,
            'DatabasePort' => $config->DatabasePort ?? '1433',
            'DatabaseUser' => $config->DatabaseUser,
            'DatabasePassword' => $config->DatabasePassword,
        ];

        return $this->executeWithCredentials($outlet, $config->SqlScript, $credentials);
    }

    /**
     * Execute SQL query with provided credentials
     */
    public function executeQuery(Outlet $outlet, string $sqlScript, ?array $credentials = null): array
    {
        if (!$outlet->IPAddress) {
            return ['success' => false, 'message' => 'Outlet IP address is not configured'];
        }

        return $this->executeWithCredentials($outlet, $sqlScript, $credentials);
    }

    /**
     * Execute using session credentials
     */
    public function executeWithSession(string $sqlScript): array
    {
        $connection = Session::get('outlet_connection');

        if (!$connection) {
            return [
                'success' => false,
                'message' => 'No outlet connection in session. Please select an outlet first.',
            ];
        }

        $outlet = Outlet::find($connection['OutletID']);

        if (!$outlet) {
            return ['success' => false, 'message' => 'Outlet not found'];
        }

        $credentials = [
            'DatabaseName' => $connection['DatabaseName'],
            'DatabasePort' => $connection['DatabasePort'],
            'DatabaseUser' => $connection['DatabaseUser'],
            'DatabasePassword' => Crypt::decryptString($connection['DatabasePassword']),
        ];

        return $this->executeWithCredentials($outlet, $sqlScript, $credentials);
    }

    /**
     * Execute SQL with credentials
     */
    protected function executeWithCredentials(Outlet $outlet, string $sqlScript, ?array $credentials): array
    {
        try {
            $connectionName = 'outlet_exec_' . $outlet->OutletID . '_' . time();

            $host = $outlet->IPAddress;
            $database = $credentials['DatabaseName'] ?? '';
            $port = $credentials['DatabasePort'] ?? '1433';
            $username = $credentials['DatabaseUser'] ?? '';
            $password = $credentials['DatabasePassword'] ?? '';

            if (empty($database) || empty($username)) {
                return ['success' => false, 'message' => 'Database credentials are required'];
            }

            Config::set("database.connections.{$connectionName}", [
                'driver' => 'sqlsrv',
                'host' => $host,
                'port' => $port,
                'database' => $database,
                'username' => $username,
                'password' => $password,
                'charset' => 'utf8',
                'prefix' => '',
                'encrypt' => 'no',
                'trust_server_certificate' => true,
            ]);

            DB::connection($connectionName)->beginTransaction();

            try {
                // Check if it's a SELECT query
                $isSelect = preg_match('/^\s*SELECT/i', trim($sqlScript));

                if ($isSelect) {
                    $results = DB::connection($connectionName)->select($sqlScript);
                    DB::connection($connectionName)->commit();
                    DB::purge($connectionName);

                    return [
                        'success' => true,
                        'message' => 'Query executed successfully. Rows returned: ' . count($results),
                        'data' => $results,
                        'rows_count' => count($results),
                    ];
                } else {
                    DB::connection($connectionName)->unprepared($sqlScript);
                    $affectedRows = DB::connection($connectionName)->select('SELECT @@ROWCOUNT as affected')[0]->affected ?? 0;

                    DB::connection($connectionName)->commit();
                    DB::purge($connectionName);

                    Log::info('Outlet script executed', [
                        'outlet_id' => $outlet->OutletID,
                        'outlet_ip' => $outlet->IPAddress,
                        'affected_rows' => $affectedRows,
                    ]);

                    return [
                        'success' => true,
                        'message' => "Script executed successfully. Affected rows: {$affectedRows}",
                        'affected_rows' => $affectedRows,
                    ];
                }

            } catch (Exception $e) {
                DB::connection($connectionName)->rollBack();
                DB::purge($connectionName);
                throw $e;
            }

        } catch (Exception $e) {
            if (isset($connectionName)) {
                DB::purge($connectionName);
            }

            Log::error('Outlet script execution failed', [
                'outlet_id' => $outlet->OutletID,
                'outlet_ip' => $outlet->IPAddress,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get data from session outlet
     */
    public function selectFromSession(string $table, array $columns = ['*'], array $where = []): array
    {
        $connection = Session::get('outlet_connection');

        if (!$connection) {
            return [
                'success' => false,
                'message' => 'No outlet connection in session.',
            ];
        }

        $outlet = Outlet::find($connection['OutletID']);

        if (!$outlet) {
            return ['success' => false, 'message' => 'Outlet not found'];
        }

        try {
            $connectionName = 'outlet_select_' . $outlet->OutletID . '_' . time();

            Config::set("database.connections.{$connectionName}", [
                'driver' => 'sqlsrv',
                'host' => $outlet->IPAddress,
                'port' => $connection['DatabasePort'] ?? '1433',
                'database' => $connection['DatabaseName'],
                'username' => $connection['DatabaseUser'],
                'password' => Crypt::decryptString($connection['DatabasePassword']),
                'charset' => 'utf8',
                'prefix' => '',
                'encrypt' => 'no',
                'trust_server_certificate' => true,
            ]);

            $query = DB::connection($connectionName)->table($table)->select($columns);

            foreach ($where as $column => $value) {
                $query->where($column, $value);
            }

            $results = $query->get();

            DB::purge($connectionName);

            return [
                'success' => true,
                'data' => $results,
            ];

        } catch (Exception $e) {
            if (isset($connectionName)) {
                DB::purge($connectionName);
            }

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
