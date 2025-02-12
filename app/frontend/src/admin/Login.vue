<template>
    <div class="row g-0 app-auth-wrapper">
	    <div class="col-12 col-md-7 col-lg-6 auth-main-col text-center p-5">
		    <div class="d-flex flex-column align-content-end">
			    <div class="app-auth-body mx-auto">	
				    <div class="app-auth-branding mb-4"><router-link class="app-logo" to="/admin"><img class="logo-icon me-2" src="/src/assets/images/app-logo.svg" alt="logo"></router-link></div>
					<h2 class="auth-heading text-center mb-5">Log in to HadiStore</h2>
			        <div class="auth-form-container text-start">
						<form class="auth-form login-form" @submit.prevent="submitForm">         
							<div class="email mb-3">
								<label class="sr-only" for="signin-email">Email</label>
								<input id="signin-email" v-model="form.email" name="signin-email" type="email" class="form-control signin-email" placeholder="Email address" required>
							</div><!--//form-group-->
							<div class="password mb-3">
								<label class="sr-only" for="signin-password">Password</label>
								<input id="signin-password" v-model="form.password" name="signin-password" type="password" class="form-control signin-password" placeholder="Password" required>
								<div class="extra mt-3 row justify-content-between">
									<div class="col-6">
										<div class="form-check">
											<input class="form-check-input" type="checkbox" value="" id="RememberPassword">
											<label class="form-check-label" for="RememberPassword">
											Remember me
											</label>
										</div>
									</div><!--//col-6-->
									<div class="col-6">
										<div class="forgot-password text-end">
											<a href="reset-password.html">Forgot password?</a>
										</div>
									</div><!--//col-6-->
								</div><!--//extra-->
							</div><!--//form-group-->
							<div class="text-center">
								<button type="submit" class="btn app-btn-primary w-100 theme-btn mx-auto">Log In</button>
							</div>
						</form>
						
						<div class="auth-option text-center pt-5">No Account? Sign up <router-link class="text-link" :to="'/admin/register'">here</router-link>.</div>
					</div><!--//auth-form-container-->	

			    </div><!--//auth-body-->
		    
		    </div><!--//flex-column-->   
	    </div><!--//auth-main-col-->
	    <div class="col-12 col-md-5 col-lg-6 h-100 auth-background-col">
		    <div class="auth-background-holder":style="{
					backgroundImage: 'url(' + backgroundImage + ')',
					backgroundRepeat: 'no-repeat',
					backgroundPosition: 'center center',
					backgroundSize: 'cover',
					height: '100vh',
					minHeight: '100%'
				}">
		    </div>
		    <div class="auth-background-mask"></div>
		    <div class="auth-background-overlay p-3 p-lg-5">
			    <div class="d-flex flex-column align-content-end h-100">
				    <div class="h-100"></div>
				</div>
		    </div><!--//auth-background-overlay-->
	    </div><!--//auth-background-col-->
    
    </div><!--//row-->
</template>

<script>
import backgroundImage from '@/assets/images/background/background-2.jpg';

import apiService from '@/services/apiService';
import showAlert from "@/services/swalAlert";

export default {
	data(){
		return {
			backgroundImage,

			form : {
				email: '',
				password: ''
			}
		}
	},
	methods : {
		async submitForm(){
            try{
                const response = await apiService.adminLogin(this.form);

                if(response.data.success == "true"){

                    console.log(response.data);
                    localStorage.setItem('admin', JSON.stringify(response.data.admin));
                    localStorage.setItem('admin_token', response.data.token);
                    this.$swal({
                        title: 'Success!',
                        text: response.data.message,
                        icon: 'success',
                        confirmButtonText: 'OK',
                        customClass: {
                            confirmButton: 'custom-confirm-button'
                        }
                    }).then(() =>{
                        this.$router.push('/admin');
                    });
                    
                }else{
					showAlert("error", "Oops!", response.data.message);
                    console.log(response.data);
                }
            }catch(error){
                console.error("Error occurs because " , error);
            }
        }
	}
}

</script>