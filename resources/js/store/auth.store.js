import { defineStore } from 'pinia'
import axiosClient from "../axios";
import { encrypt, decrypt } from '@/helpers/EncryptionHelper';
export const authStore = defineStore({
    id: 'auth',
    state: () => {
        return {
            user: JSON.parse(decrypt(localStorage.getItem('_usr'))),
        }
    },
    actions: {
        async login(user) {
            return axiosClient.post('/login', user)
                .then(({data}) => {
                    if (!data.error) {
                        this.user = data
                        localStorage.setItem('_usr', encrypt(JSON.stringify(data)));
                    }
                    return data;
                }).catch((errors) => {
                    console.log(errors)
                    return errors.response.data.errors
                })
        },
        register(user) {
            return axiosClient.post('/register', user)
                .then(({data}) => {
                    if (data.token) {
                        this.user = data
                        localStorage.setItem('_usr', encrypt(JSON.stringify(data)));
                        return {result: true}
                    }else{
                        return data
                    }
                }).catch((errors) => {
                    return errors.response.data.errors
                })
        },
        logout() {
            return axiosClient.post('/logout').then(data => {
                if (data.data.success) {
                    localStorage.removeItem('_usr')
                    //this.user = JSON.parse(decrypt(localStorage.getItem('_usr')))
                    return true
                } else {
                    return false
                }
            }).catch(() => {
                return false
            })
        },
        async updateUserInfo() {
            return axiosClient.get('/user').then(({data}) => {
                const token = this.user.token
                this.user = data
                this.user.token = token
                localStorage.setItem('_usr', encrypt(JSON.stringify(this.user)))
            }).catch(error => {
                return this.user
            });
        }
    }
});
