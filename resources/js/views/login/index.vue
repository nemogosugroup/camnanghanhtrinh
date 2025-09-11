<template>
    <div>
        <div class="celebration-overlay" aria-hidden="true"></div>
        <div class="login-container" :style="backgroundLogin">
            <div class="login-card">
                <div class="top-accent"></div>
                <div class="login-content">
                    <div class="gosu-logo">
                        <div class="gosu-icon"><img :src="logo" alt="gosu">
                        </div>
                        <div class="gosu-text">LOGIN</div>
                    </div>

                    <h4 class="login-title">Sign In to your account</h4>

                    <form method="post" action="" id="loginForm">
                        <input type="hidden" name="submit" value="1">
                        <input type="hidden" name="_remember_me" value="1">

                        <div class="form-group mb-3 input-row">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                <input type="text" class="form-control" required name="_username" id="email" ref="email"
                                       v-model="loginForm.email"
                                       placeholder="Email">
                            </div>
                        </div>

                        <div class="form-group mb-3 input-row">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                <input type="password" class="form-control" required name="_password" id="password"
                                       ref="password" v-model="loginForm.password"
                                       placeholder="Mật khẩu">
                                <button class="btn btn-outline-secondary password-toggle" type="button" id="togglePassword">
                                    <i class="fa fa-eye"></i></button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-login" id="loginBtn" @click.prevent="handleLogin" style="height:54px;border-radius:12px;">
                            <span class="btn-text"><i class="fa fa-arrow-right" style="margin-right:8px;"></i>Login</span>
                            <span class="btn-loading" style="display:none;">
                            <i class="fa fa-spinner fa-spin" style="margin-right:8px;"></i>Đang xử lý...
                        </span>
                        </button>
                    </form>

                    <div class="info-text">
                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                        Hệ thống xác thực GOSU
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import bgLogin from "@/assets/images/bg/login-bg.jpg";
import imagesLogo from "@/assets/images/logo/gosu-14.png";
import {getAccessToken} from "@/utils/auth";

export default {
    name: "Login",
    data() {
        return {
            loginForm: {
                email: "",
                password: "",
            },
            loginRules: {
                email: [
                    {
                        required: true,
                        type: 'email',
                        trigger: "blur",
                        message: "Vui lòng nhập địa chỉ email!",
                    },
                ],
                password: [
                    {
                        required: true,
                        trigger: "blur",
                        message: "Vui lòng nhập mật khẩu!",
                    },
                ],
            },
            passwordType: "password",
            capsTooltip: false,
            loading: false,
            showDialog: false,
            redirect: undefined,
            redirectUri: undefined,
            oAuthState: undefined,
            otherQuery: {},
            bgLogin: bgLogin,
            logo: imagesLogo,
            fontLink: null,
        };
    },
    watch: {
        $route: {
            handler: function (route) {
                const query = route.query;
                // redirect_uri
                if (query) {
                    this.redirect = query.redirect;
                    this.redirectUri = query.redirect_uri;
                    this.oAuthState = query.state;
                    this.otherQuery = this.getOtherQuery(query);
                }
            },
            immediate: true,
        },
    },
    created() {
    },
    mounted() {
        // Tạo một thẻ <link> mới
        this.fontLink = document.createElement('link');
        this.fontLink.rel = 'stylesheet';
        this.fontLink.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css';

        // Thêm thẻ <link> vào <head>
        document.head.appendChild(this.fontLink);
        
        this.handleAnimLogin();
    },
    beforeUnmount() {
        // Xóa thẻ <link> khi component bị hủy
        if (this.fontLink) {
            document.head.removeChild(this.fontLink);
        }
    },
    destroyed() {
    },
    computed: {
        backgroundLogin() {
            return `background: url(${this.bgLogin}) center center / cover no-repeat fixed;`;
        },
    },
    methods: {
        checkCapslock(e) {
            const {key} = e;
            this.capsTooltip =
                key && key.length === 1 && key >= "A" && key <= "Z";
        },
        handleLogin() {
            this.loading = true;
            this.$store
                .dispatch("user/login", this.loginForm)
                .then((response) => {
                    const {data} = response;
                    if (data.success) {
                        this.$message({
                            message: data.message,
                            type: 'success'
                        })

                        //console.log("check this.redirectUri",this.redirectUri);
                        if (this.redirectUri) {

                            //console.log("check this.redirectUri 2",this.redirectUri);
                            window.location.href = this.redirectUri + '?token=' + getAccessToken() + '&state=' + this.oAuthState;
                        } else {
                            //console.log("check this.redirect",this.redirect);
                            // this.$router.push({
                            //     path: this.redirect || "/",
                            //     query: this.otherQuery,
                            // });
                            this.$router.push({
                                path: this.redirect || "/vulan/",
                                query: this.otherQuery,
                            });
                        }


                    } else {
                        this.$message({
                            message: data.message,
                            type: 'error'
                        })
                    }
                    this.loading = false;
                })
                .catch(() => {
                    this.loading = false;
                });
        },
        getOtherQuery(query) {
            return Object.keys(query).reduce((acc, cur) => {
                if (cur !== "redirect") {
                    acc[cur] = query[cur];
                }
                return acc;
            }, {});
        },

        handleAnimLogin() {
            (function(){
                var toggle = document.getElementById('togglePassword');
                if(toggle){
                    toggle.addEventListener('click', function(){
                        var input = document.getElementById('password');
                        var icon = this.querySelector('i');
                        if(input.type === 'password'){
                            input.type = 'text';
                            icon.classList.remove('fa-eye');
                            icon.classList.add('fa-eye-slash');
                        }else{
                            input.type = 'password';
                            icon.classList.remove('fa-eye-slash');
                            icon.classList.add('fa-eye');
                        }
                    });
                }

                var form = document.getElementById('loginForm');
                var loginBtn = document.getElementById('loginBtn');
                if(form && loginBtn){
                    form.addEventListener('submit', function(){
                        loginBtn.classList.add('loading');
                        loginBtn.querySelector('.btn-loading').style.display = 'inline-flex';
                    });

                    loginBtn.addEventListener('click', function(e){
                        var rect = this.getBoundingClientRect();
                        var size = Math.max(rect.width, rect.height);
                        var circ = document.createElement('span');
                        circ.className = 'ripple-circ';
                        circ.style.width = size + 'px';
                        circ.style.height = size + 'px';
                        circ.style.left = (e.clientX - rect.left - size/2) + 'px';
                        circ.style.top = (e.clientY - rect.top - size/2) + 'px';
                        this.appendChild(circ);
                        setTimeout(function(){ if(circ && circ.parentNode){ circ.parentNode.removeChild(circ); } }, 650);
                    });
                }

                // Anniversary confetti
                try {
                    const overlay = document.querySelector('.celebration-overlay');
                    if (overlay) {
                        const colors = ['#ff8c42', '#ffd700', '#667eea', '#ffffff'];
                        const confettiCount = 48;
                        for (let i = 0; i < confettiCount; i++) {
                            const piece = document.createElement('span');
                            piece.className = 'confetti';
                            piece.style.left = (Math.random() * 100) + '%';
                            piece.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                            piece.style.animationDuration = (8 + Math.random() * 8) + 's';
                            piece.style.animationDelay = (-Math.random() * 6) + 's';
                            piece.style.opacity = (0.6 + Math.random() * 0.4).toString();
                            overlay.appendChild(piece);
                        }
                        // Cleanup to avoid long-living DOM nodes
                        setTimeout(() => { overlay.innerHTML = ''; }, 18000);
                    }
                } catch (e) {
                    console.debug('Confetti init skipped:', e);
                }
            })();
        }
    },
};
</script>

<style>
.celebration-overlay .confetti {
    position: absolute;
    top: -10%;
    width: 6px;
    height: 16px;
    border-radius: 2px;
    opacity: 0.9;
    animation: confetti-fall linear infinite;
}

@keyframes confetti-fall {
    0% { transform: translateY(-10%) rotate(0deg); }
    100% { transform: translateY(120vh) rotate(360deg); }
}
</style>

<style lang="css" scoped>
html, body {
    height: 100%;
}

body {
    background: linear-gradient(135deg, #667eea 0%, #ff8c42 100%);
    min-height: 100vh;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 0;
}

.anniversary-banner {
    position: fixed;
    top: 20px;
    left: 0;
    right: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 12px 16px;
    z-index: 1000;
    pointer-events: none;
}

.anniversary-logo {
    margin-bottom: 8px;
    pointer-events: none;
}

.anniversary-logo img {
    height: 56px;
    width: auto;
    display: block;
    filter: drop-shadow(0 6px 12px rgba(0,0,0,0.35));
    animation: badge-pop 0.9s ease;
}

.anniversary-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 140, 66, 0.95);
    color: #fff;
    padding: 8px 16px;
    border-radius: 999px;
    box-shadow: 0 6px 18px rgba(255, 140, 66, 0.35);
    font-weight: 700;
    letter-spacing: 0.5px;
    transform: translateY(0);
    animation: badge-pop 1s ease;
}

.anniversary-title {
    text-transform: uppercase;
}

.anniversary-subtitle {
    margin-top: 6px;
    background: rgba(102, 126, 234, 0.95);
    color: #fff;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 12px;
    box-shadow: 0 6px 18px rgba(102, 126, 234, 0.35);
}

@keyframes badge-pop {
    0% { transform: scale(0.8); opacity: 0; }
    60% { transform: scale(1.05); opacity: 1; }
    100% { transform: scale(1); }
}

.celebration-overlay {
    position: fixed;
    inset: 0;
    pointer-events: none;
    overflow: hidden;
    z-index: 999;
}

.celebration-overlay::before,
.celebration-overlay::after {
    content: '';
    position: absolute;
    top: -10%;
    left: 0;
    right: 0;
    height: 120%;
    background-size: 300px 300px, 260px 260px, 220px 220px, 240px 240px;
    filter: blur(1px);
    opacity: 0.5;
    animation: float-particles 14s linear infinite;
}

.celebration-overlay::after {
    animation-duration: 18s;
    opacity: 0.35;
}

@keyframes float-particles {
    0% { transform: translateY(-5%); }
    50% { transform: translateY(5%); }
    100% { transform: translateY(-5%); }
}
.fa-lock{
    content: "\f023";
    font-size: 19px !important;
    padding: 1px !important;
}
@media (max-width: 576px) {
    .anniversary-badge { padding: 6px 12px; font-size: 14px; }
    .anniversary-subtitle { font-size: 11px; }
}

.login-container {
    min-height: 100vh;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    padding: 20px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.login-card {
    background: rgba(255, 255, 255, 0.98);
    border-radius: 25px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    -webkit-backdrop-filter: blur(15px);
    backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    max-width: 450px;
    width: 100%;
    overflow: hidden;
    -webkit-animation: rise .48s ease both;
    animation: rise .48s ease both;
}

.login-card .top-accent {
    height: 4px;
    background: linear-gradient(135deg, #667eea 0%, #ff8c42 100%);
}

.login-content {
    padding: 2.2rem;
}

.gosu-logo {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    gap: 12px;
    -ms-flex-pack: distribute;
    justify-content: center;
    margin-bottom: 18px;
}

.gosu-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    -webkit-animation: float 3s ease-in-out infinite;
    animation: float 3s ease-in-out infinite;
}

.gosu-icon img {
    height: 60px;
    border-radius: 12px;
}

.login-title {
    text-align: center;
    margin: 12px 0 18px;
    color: #2b3a67;
    font-weight: 600;
    font-size: 18px;
}

.input-row {
    margin-bottom: 1rem;
}

.form-control {
    border-radius: 15px;
    border: 2px solid #e9ecef;
    padding: 0 1.2rem;
    font-size: 1.5rem;
    transition: all .3s ease;
    background: rgba(255, 255, 255, 0.9);
    height: 52px;
}

.input-group .form-control {
    border-left: none;
    border-radius: 0 15px 15px 0;
}

#email {
    border: 2px solid #e9ecef;
}

#password {
    border: 2px solid #e9ecef;
    border-radius: 0;
}

#email:focus,
#password:focus {
    border-color: #ff8c42;
    outline: none;
    box-shadow: 0 0 0 0.25rem rgba(255, 140, 66, 0.15);
    background: #ffffff;
}

.input-group-text {
    width: 53px;
    height: 52px;
    padding: 0 18px;
    font-size: 21px;
    line-height: initial;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    background: #ff8c42;
    color: #ffffff;
    border: 2px solid #ff8c42;
    border-right: none;
    border-radius: 15px 0 0 15px;
    text-align: center;
}
.input-group-text > .fa-envelope {
    font-size: 15px;
}

.password-toggle {
    background: #ffffff;
    color: #ff8c42;
    border: 2px solid #e9ecef;
    border-left: none;
    border-radius: 0 15px 15px 0;
    cursor: pointer;
    text-align: center;
    height: 52px;
    display: inline-flex;
    align-items: center;
    padding: 0 18px;
}

.form-control:focus {
    border-color: #ff8c42;
    outline: none;
    box-shadow: 0 0 0 3px rgba(255, 140, 66, 0.15);
    background: #ffffff;
}

.btn-login {
    width: 100%;
    background: #667eea;
    border: none;
    color: #ffffff;
    padding: 1rem;
    font-weight: 600;
    font-size: 1rem;
    border-radius: 15px;
    transition: all .3s ease;
    position: relative;
    overflow: hidden;
}

.btn-login::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left .5s;
}

.btn-login:hover::before {
    left: 100%;
}

.btn-login:hover {
    -webkit-transform: translateY(-3px);
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, .4);
    background: #5a6fd8;
}

.btn-login:active {
    transform: translateY(1px) scale(.99);
}

.btn-login.loading .btn-text {
    display: none;
}

.btn-login.loading .btn-loading {
    display: inline-flex !important;
    align-items: center;
}

.info-text {
    text-align: center;
    color: #888;
    font-size: 15px;
    margin-top: 1.2rem;
}

.checkbox input[type=checkbox] {
    accent-color: #6d8efb;
}

.alert {
    border-radius: 15px;
    border: none;
    padding: 1rem 1.2rem;
    margin-bottom: 1.5rem;
}

.input-group {
    position: relative;
    display: flex;
    flex-wrap: wrap;
    align-items: stretch;
    width: 100%;
}

.input-group > .form-control, .input-group > .form-floating, .input-group > .form-select {
    position: relative;
    flex: 1 1 auto;
    width: 1%;
    min-width: 0;
    font-size: 15px;
}

.form-control {
    border-left: none;
    border-radius: 0 15px 15px 0;
}

.form-control {
    border-radius: 15px;
    border: 2px solid #e9ecef;
    padding: 1rem 1.2rem;
    font-size: 1.5rem;
    transition: all 0.3s ease;
    background: rgba(255, 255, 255, 0.9);
}

.form-control {
    display: block;
    width: 100%;
    padding: 7.5px;
    font-weight: 400;
    line-height: 1.5;
    color: var(--bs-body-color);
    background-color: var(--bs-body-bg);
    background-clip: padding-box;
    border: var(--bs-border-width) solid var(--bs-border-color);
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    border-radius: var(--bs-border-radius);
    transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
}

.gosu-icon {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    font-weight: bold;
    color: white;
    border-radius: 12px;
    animation: 3s ease-in-out 0s infinite normal none running float;
}

.gosu-text {
    font-size: 32px;
    font-weight: 700;
    color: rgb(255, 140, 66);
    margin-left: 20px;
}

.alert-danger {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
    color: white;
}

.alert {
    border-radius: 15px;
    border: none;
    padding: 1rem 1.2rem;
    margin-bottom: 1.5rem;
}

.alert-danger {
    --bs-alert-color: var(--bs-danger-text-emphasis);
    --bs-alert-bg: var(--bs-danger-bg-subtle);
    --bs-alert-border-color: var(--bs-danger-border-subtle);
    --bs-alert-link-color: var(--bs-danger-text-emphasis);
}

.alert {
    --bs-alert-bg: transparent;
    --bs-alert-padding-x: 1rem;
    --bs-alert-padding-y: 1rem;
    --bs-alert-margin-bottom: 1rem;
    --bs-alert-color: inherit;
    --bs-alert-border-color: transparent;
    --bs-alert-border: var(--bs-border-width) solid var(--bs-alert-border-color);
    --bs-alert-border-radius: var(--bs-border-radius);
    --bs-alert-link-color: inherit;
    position: relative;
    padding: var(--bs-alert-padding-y) var(--bs-alert-padding-x);
    margin-bottom: var(--bs-alert-margin-bottom);
    color: var(--bs-alert-color);
    background-color: var(--bs-alert-bg);
    border: var(--bs-alert-border);
    border-radius: var(--bs-alert-border-radius);
}

@-webkit-keyframes rise {
    from {
        opacity: 0;
        -webkit-transform: translate3d(0, 12px, 0) scale(.985);
        transform: translate3d(0, 12px, 0) scale(.985);
    }
    to {
        opacity: 1;
        -webkit-transform: none;
        transform: none;
    }
}

@-webkit-keyframes rise {
    from {
        opacity: 0;
        -webkit-transform: translate3d(0, 12px, 0) scale(.985);
        transform: translate3d(0, 12px, 0) scale(.985);
    }
    to {
        opacity: 1;
        -webkit-transform: none;
        transform: none;
    }
}

@keyframes rise {
    from {
        opacity: 0;
        -webkit-transform: translate3d(0, 12px, 0) scale(.985);
        transform: translate3d(0, 12px, 0) scale(.985);
    }
    to {
        opacity: 1;
        -webkit-transform: none;
        transform: none;
    }
}

@-webkit-keyframes slide-accent {
    0% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0% 50%;
    }
}

@keyframes slide-accent {
    0% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0% 50%;
    }
}

@-webkit-keyframes float {
    0%, 100% {
        -webkit-transform: translateY(0);
        transform: translateY(0);
    }
    50% {
        -webkit-transform: translateY(-10px);
        transform: translateY(-10px);
    }
}

@keyframes float {
    0%, 100% {
        -webkit-transform: translateY(0);
        transform: translateY(0);
    }
    50% {
        -webkit-transform: translateY(-10px);
        transform: translateY(-10px);
    }
}
</style>
