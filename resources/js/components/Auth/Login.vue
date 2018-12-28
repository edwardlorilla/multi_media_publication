<template>
    <div class="page-login">
        <div class="login-box">
            <div class="login-title">
                <h3>Multi Media Publication</h3>
                <p>Log in</p>
                </div>
            <div class="login-form">
                <el-form label-position="top" :model="loginForm" :rules="loginRule" ref="loginForm">
                    <el-form-item prop="email">
                        <el-input placeholder="Enter Email" type="text" v-model="loginForm.email" auto-complete="off"></el-input>
                        </el-form-item>
                    <el-form-item prop="password">
                        <el-input placeholder="Enter Password" type="password" v-model="loginForm.password" auto-complete="off"></el-input>
                        </el-form-item>
                    <el-form-item>
                        <el-button type="primary" :loading="loading" @click="handleSubmit">Login</el-button>
                        </el-form-item>
                    </el-form>
                </div>
            </div>
        </div>
    </template>

<script>
    export default {
        name: 'login',
        data () {
            return {
                loginRule: {
                    email: [
                        {
                            require: true,
                            message: ' '
                        }
                    ]
                },
                loginForm: {
                    email: '',
                    password: ''
                },
                loading: false
            }
        },
        methods: {
            handleSubmit () {
                var vm = this

                this.$refs.loginForm.validate((valid) => {
                    if (valid) {
                        vm.loading = true
                        axios.post('/login', vm.loginForm).then(function () {
                            vm.loading = false
                            window.location.href = window.location.href;
                        }).catch(function (error) {
                            vm.loading = false
                            if (error.response.data.errors && error.response.data.message) {
                                vm.errors = error.response.data.errors;
                                vm.$message({message: error.response.data.message, type: 'error'})
                            }
                        })
                    }
                })
            }
        }
    }
    </script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scope>
    @import url('./auth.css');
</style>