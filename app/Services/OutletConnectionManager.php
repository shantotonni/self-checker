<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Models\Outlet;
use Illuminate\Support\Facades\Log;

class OutletConnectionManager
{
    // ✅ Main database connection - for outlets table
    protected $mainConnection = 'sqlsrv';

    // ✅ Dynamic outlet database connection
    protected $outletConnection = 'outlet_dynamic';

    /**
     * Connect to a specific outlet's database
     */
    public function connectToOutlet($outletId)
    {
        // ✅ Fetch outlet info from MAIN database
        $outlet = Outlet::on($this->mainConnection)
            ->where('OutletID', $outletId)
            ->where('Status', 'Active')
            ->where('IsDeleted', 0)
            ->first();

        if (!$outlet) {
            throw new \Exception('Outlet not found or inactive');
        }

        // Validate required fields
        if (!$outlet->IPAddress) {
            throw new \Exception('IP Address not configured');
        }

        if (!$outlet->DatabaseName) {
            throw new \Exception('Database name not configured');
        }

        if (!$outlet->DatabaseUser) {
            throw new \Exception('Database user not configured');
        }

        $password = trim($outlet->DatabasePassword ?? '');

        Log::info('Connecting to outlet database', [
            'outlet_id' => $outletId,
            'outlet_name' => $outlet->OutletName,
            'ip' => $outlet->IPAddress,
            'database' => $outlet->DatabaseName,
        ]);

        try {
            // ✅ Configure DYNAMIC outlet connection
            Config::set("database.connections.{$this->outletConnection}", [
                'driver' => 'sqlsrv',
                'host' => $outlet->IPAddress,
                'port' => '1433',
                'database' => $outlet->DatabaseName,
                'username' => $outlet->DatabaseUser,
                'password' => Crypt::decryptString($password),
                'charset' => 'utf8',
                'prefix' => '',
                'prefix_indexes' => true,
                'options' => [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                ],
            ]);

            // Clear existing connection
            DB::purge($this->outletConnection);

            // Test connection with a simple query
            DB::connection($this->outletConnection)->getPdo();
            DB::connection($this->outletConnection)->select('SELECT 1 as test');

            Log::info('Successfully connected to outlet database');

            return [
                'success' => true,
                'outlet' => $outlet,
                'connection' => $this->outletConnection
            ];

        } catch (\Exception $e) {
            Log::error('Outlet database connection failed', [
                'outlet_id' => $outletId,
                'error' => $e->getMessage()
            ]);

            throw new \Exception('Database connection failed: ' . $e->getMessage());
        }
    }

    /**
     * Get the dynamic outlet connection
     */
    public function getOutletConnection()
    {
        return DB::connection($this->outletConnection);
    }

    /**
     * Get the main database connection
     */
    public function getMainConnection()
    {
        return DB::connection($this->mainConnection);
    }

    /**
     * Check if outlet connection is active
     */
    public function isOutletConnected()
    {
        try {
            $result = DB::connection($this->outletConnection)
                ->select('SELECT 1 as test');
            return !empty($result);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Disconnect outlet database
     */
    public function disconnectOutlet()
    {
        try {
            DB::purge($this->outletConnection);
        } catch (\Exception $e) {
            Log::warning('Error disconnecting outlet: ' . $e->getMessage());
        }
    }

    /**
     * Get current outlet info from session
     */
    public function getCurrentOutlet()
    {
        $outletId = session('active_outlet_id');

        if (!$outletId) {
            return null;
        }

        return Outlet::on($this->mainConnection)
            ->find($outletId);
    }
}
