export default {
    state:{
        token: localStorage.getItem('token') || '',
        allCustomer:{},
        allUserMenu:{},
    },
    getters:{
        getAllCustomer(state){
            return state.allCustomer
        },
        getAllUserMenu(state){
            return state.allUserMenu
        }
    },
    actions:{
        getAllUserMenu(context){
            axios.get('api/sidebar-get-all-user-menu').then((response)=>{
                console.log(response)
                context.commit('getAllUserMenu',response.data.user_menu);
            }).catch((error)=>{
                if(error.response.data.status == 401){
                    localStorage.removeItem('token');
                    window.location.href = '/harvester/login';
                }
            })
        }
    },
    mutations:{
        getAllCustomer(state, data){
            return state.allCustomer = data;
        },
        getAllUserMenu(state, data){
            return state.allUserMenu = data;
        },
        setToken(state,token){
            localStorage.setItem('token',token);
            state.token = token
        },
        clearToken(state){
            localStorage.removeItem('token');
            state.token = '';
        }
    }
}
