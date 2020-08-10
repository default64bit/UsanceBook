import axios from 'axios';

const getDefaultState = () => {
    return {
        data: {
            avatar: '',
            name: '',
            family: '',
            email: '',
            phone: '',
        },
        isLoggedIn: false,
    }
}

const state = getDefaultState();

const getters = {
    userData: (state) => state.data,
    isLoggedIn: (state) => state.isLoggedIn
};

const mutations = {
    setUserData: (state,userData) => (state.data = userData),
    setIsLoggedIn: (state,isLoggedIn) => (state.isLoggedIn = isLoggedIn),
    reset: (state) => (state = getDefaultState()),
};

const actions = {
    async getUser({commit},access_token){
        await axios({
            url: '/api/v1/user',
            method: 'get',
            data: {},
            headers: {
                'Authorization': `Bearer ${access_token}`,
                'Content-Type': 'application/json'
            },
        }).then(response=>{
            response.data
            commit('setUserData',{
                avatar: response.data.avatar,
                name: response.data.name,
                family: response.data.family,
                email: response.data.email,
                phone: response.data.phone,
            });
            commit('setIsLoggedIn',true);
        }).catch(error=>{
            commit('reset');
            console.log(error);
        });
    },
};

export default {
    state,
    getters,
    actions,
    mutations
};