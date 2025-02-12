<template>
	<div class="row g-0 app-auth-wrapper">
	  <div class="col-12 col-md-7 col-lg-6 auth-main-col text-center p-5">
		<div class="d-flex flex-column align-content-end">
		  <div class="app-auth-body mx-auto">
			<div class="app-auth-branding mb-4">
			  <router-link class="app-logo" to="/admin">
				<img class="logo-icon me-2" src="/src/assets/images/app-logo.svg" alt="logo" />
			  </router-link>
			</div>
			<h2 class="auth-heading text-center mb-4">Sign up to HadiStore</h2>
  
			<div class="auth-form-container text-start mx-auto">
			  <form class="auth-form auth-signup-form" @submit.prevent="submitForm">
				<div class="email mb-3">
				  <label class="sr-only" for="signup-name">Your Name</label>
				  <input
				  	v-model="form.first_name"
					id="signup-name"
					name="signup-name"
					type="text"
					class="form-control signup-name"
					placeholder="First name"
					required
				  />
				</div>
				<div class="email mb-3">
				  <label class="sr-only" for="signup-name">Your Name</label>
				  <input
				  	v-model="form.last_name"
					id="signup-name"
					name="signup-name"
					type="text"
					class="form-control signup-name"
					placeholder="Last name"
					required
				  />
				</div>
				<div class="email mb-3">
				  <label class="sr-only" for="signup-email">Your Email</label>
				  <input
				  	v-model="form.email"
					id="signup-email"
					name="signup-email"
					type="email"
					class="form-control signup-email"
					placeholder="Email"
					required
				  />
				</div>
				<div class="password mb-3">
				  <label class="sr-only" for="signup-password">Password</label>
				  <input

				 	v-model="form.password"
					id="signup-password"
					name="signup-password"
					type="password"
					class="form-control signup-password"
					placeholder="Create a password"
					required
				  />
				</div>
				<div class="password mb-3">
				  <label class="sr-only" for="signup-password">Password</label>
				  <input
				  	v-model="form.confirm_password"
					id="signup-password"
					name="signup-password"
					type="password"
					class="form-control signup-password"
					placeholder="Confirm password"
					required
				  />
				</div>
				<div class="extra mb-3">
				  <div class="form-check">
					<input class="form-check-input" type="checkbox" id="RememberPassword" />
					<label class="form-check-label" for="RememberPassword">
					  I agree to HadiStore's
					  <a href="#" class="app-link">Terms of Service</a> and
					  <a href="#" class="app-link">Privacy Policy</a>.
					</label>
				  </div>
				</div>
				<div class="text-center">
				  <button type="submit" class="btn app-btn-primary w-100 theme-btn mx-auto">
					Sign Up
				  </button>
				</div>
			  </form>
			  <div class="auth-option text-center pt-5">
				Already have an account?
				<router-link class="text-link" to="/admin/login">Log in</router-link>
			  </div>
			</div>
		  </div>

		</div>
	  </div>
	  <div class="col-12 col-md-5 col-lg-6 h-100 auth-background-col">
		<div
			class="auth-background-holder"
			:style="{
			backgroundImage: 'url(' + backgroundImage + ')',
			backgroundRepeat: 'no-repeat',
			backgroundPosition: 'center center',
			backgroundSize: 'cover',
			height: '100vh',
			minHeight: '100%'
			}"
		></div>
		<div class="auth-background-mask"></div>
		<div class="auth-background-overlay p-3 p-lg-5">
		  <div class="d-flex flex-column align-content-end h-100">
			<div class="h-100">  </div>
		  </div>
		</div>
	  </div>
	</div>
  </template>
  
  <script>
	import backgroundImage from '@/assets/images/background/background-2.jpg';
	import apiRequest from '@/services/apiService';

	export default {
		data() {
			return {
				backgroundImage,

				form:{
					first_name : '',
					last_name : '',
					email : '',
					password : '',
					confirm_password : ''
				}
			};
		},
		methods: {
			async submitForm(){
				try{
					const response = await apiRequest.adminRegister(this.form);

					if(response.data.success == "true"){
						this.$swal({
							title: 'Success!',
							text: response.data.message,
							icon: 'success',
							confirmButtonText: 'OK',
							customClass: {
								confirmButton: 'custom-confirm-button'
							}
						}).then(() =>{
							localStorage.setItem('token', response.data.token);
							this.$router.push('/admin/login');
						});
					}else{
						this.$swal({
							title: 'Oops!',
							text: response.data.message,
							icon: 'error',
							confirmButtonText: 'Try Again',
							customClass: {
								confirmButton: 'custom-confirm-button'
							}
						});
						console.log(response.data.message);
					}
				}catch(error){
				console.log("Failed to register user because " ,error);
				}
			}
		}
	};
  </script>
  
  