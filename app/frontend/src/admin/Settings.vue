<template>
    <div class="app-wrapper">
	    
	    <div class="app-content pt-3 p-md-3 p-lg-4">
		    <div class="container-xl">			    
			    <h1 class="app-page-title">Settings</h1>
			    <hr class="mb-4">
                <div class="row g-4 settings-section">
	                <div class="col-12 col-md-4">
		                <h3 class="section-title">General</h3>
		                <div class="section-intro">Settings section intro goes here. Lorem ipsum dolor sit amet, consectetur adipiscing elit. <a href="help.html">Learn more</a></div>
	                </div>
	                <div class="col-12 col-md-8">
		                <div class="app-card app-card-settings shadow-sm p-4">
						    
						    <div class="app-card-body">
							    <form class="settings-form" @submit.prevent="formData">
								    <div class="mb-3">
									    <label for="setting-input-1" class="form-label">Business Name<span class="ms-2" data-bs-container="body" data-bs-toggle="popover" data-bs-trigger="hover focus"  data-bs-placement="top" data-bs-content="This is a Bootstrap popover example. You can use popover to provide extra info."><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-info-circle" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
  <path d="M8.93 6.588l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588z"/>
  <circle cx="8" cy="4.5" r="1"/>
</svg></span></label>
									    <input type="text" v-model="form.name" class="form-control" id="setting-input-1" value="{{ form.name }}" required>
									</div>
									<div class="mb-3">
									    <label for="setting-input-2" class="form-label">Description</label>
									    <input type="text" v-model="form.description" class="form-control" id="setting-input-2" value="{{ form.description }}" >
									</div>
									<div class="mb-3">
									    <label for="setting-input-2" class="form-label">Owner Name</label>
									    <input type="text" v-model="form.owner_name" class="form-control" id="setting-input-2" value="{{ form.owner_name }}" >
									</div>
									<div class="mb-3">
									    <label for="setting-input-2" class="form-label">Contact Name</label>
									    <input type="text" v-model="form.contact_name" class="form-control" id="setting-input-2" value="{{ form.contact_name }}" >
									</div>
									<div class="mb-3">
									    <label for="setting-input-2" class="form-label">Phone Number</label>
									    <input type="text" v-model="form.phone_number" class="form-control" id="setting-input-2" value="{{ form.phone_number }}" >
									</div>
								    <div class="mb-3">
									    <label for="setting-input-3" class="form-label">Business Email Address</label>
									    <input type="email" v-model="form.email" class="form-control" id="setting-input-3" value="{{ form.email }}">
									</div>
									<div class="mb-3">
									    <label for="setting-input-3" class="form-label">Business Address</label>
									    <input type="address" v-model="form.address" class="form-control" id="setting-input-3" value="{{ form.address }}">
									</div>
									<div class="mb-3">
									    <label for="setting-input-3" class="form-label">City</label>
									    <input type="address" v-model="form.city" class="form-control" id="setting-input-3" value="{{ form.city }}">
									</div>
									<div class="mb-3">
									    <label for="setting-input-3" class="form-label">State</label>
									    <input type="address" v-model="form.state" class="form-control" id="setting-input-3" value="{{ form.state }}">
									</div>
									<div class="mb-3">
									    <label for="setting-input-3" class="form-label">Country</label>
									    <input type="address" v-model="form.country" class="form-control" id="setting-input-3" value="{{ form.country }}">
									</div>
									<div class="mb-3">
									    <label for="setting-input-3" class="form-label">Postal Code</label>
									    <input type="address" v-model="form.postal_code" class="form-control" id="setting-input-3" value="{{ form.postal_code }}">
									</div>
									<div class="mb-3">
									    <label for="setting-input-3" class="form-label">Business Hours</label>
									    <input type="text" v-model="form.business_hours" class="form-control" id="setting-input-3" value="{{ form.business_hours }}">
									</div>
									<div class="mb-3">
									    <label for="setting-input-3" class="form-label">Founded Year</label>
									    <input type="date" v-model="form.founded_year" class="form-control" id="setting-input-3" value="{{ form.founded_year }}">
									</div>
									<button type="submit" class="btn app-btn-primary" >Save Changes</button>
							    </form>
						    </div><!--//app-card-body-->
						    
						</div><!--//app-card-->
	                </div>
                </div><!--//row-->
                <hr class="my-4">
				<div class="row g-4 settings-section">
	                <div class="col-12 col-md-4">
		                <h3 class="section-title">Data &amp; Privacy</h3>
		                <div class="section-intro">Settings section intro goes here. Morbi vehicula, est eget fermentum ornare. </div>
	                </div>
	                <div class="col-12 col-md-8">
		                <div class="app-card app-card-settings shadow-sm p-4">						    
						    <div class="app-card-body">
							    <form class="settings-form">
								    <div class="form-check mb-3">
										<input class="form-check-input" type="checkbox" value="" id="settings-checkbox-1" checked>
										<label class="form-check-label" for="settings-checkbox-1">
										    Keep user app activity history
										</label>
									</div><!--//form-check-->
									<div class="form-check mb-3">
									    <input class="form-check-input" type="checkbox" value="" id="settings-checkbox-2" checked>
										<label class="form-check-label" for="settings-checkbox-2">
										    Keep user app preferences
										</label>
									</div>
									<div class="form-check mb-3">
									    <input class="form-check-input" type="checkbox" value="" id="settings-checkbox-3">
										<label class="form-check-label" for="settings-checkbox-3">
										    Keep user app search history
										</label>
									</div>
									<div class="form-check mb-3">
									    <input class="form-check-input" type="checkbox" value="" id="settings-checkbox-4">
										<label class="form-check-label" for="settings-checkbox-4">
										    Lorem ipsum dolor sit amet
										</label>
									</div>
									<div class="form-check mb-3">
									    <input class="form-check-input" type="checkbox" value="" id="settings-checkbox-5">
										<label class="form-check-label" for="settings-checkbox-5">
										    Aenean quis pharetra metus
										</label>
									</div>
									<div class="mt-3">
									    <button type="submit" class="btn app-btn-primary" >Save Changes</button>
									</div>
							    </form>
						    </div><!--//app-card-body-->						    
						</div><!--//app-card-->
	                </div>
                </div><!--//row-->
		    </div><!--//container-fluid-->
	    </div><!--//app-content-->
	    
	    
    </div><!--//app-wrapper-->    					

</template>

<script>

import apiRequest from '@/services/apiService';
import showAlert from "@/services/swalAlert";

export default{

	data(){
		return{
			form : {
				name : '',
				description : '',
				owner_name : '',
				contact_name : '',
				phone_number : '',
				email : '',
				address : '',
				city : '',
				state : '',
				country : '',
				postal_code : '',
				business_hours : '',
				founded_year : ''
			}
		};
	},
	mounted(){
		this.getBusinessDetail();
	},
	methods : {
		async formData(){
			try{
				const response = await apiRequest.createBusinessDetails(this.form);
				if(response.data.success == "true"){
					showAlert("success", "Success!", response.data.message);
				}else{
					showAlert("error", "Oops!", response.data.message);
				}
			}catch(error){
				console.log('Catch error ' , error);
			}
		},
		async getBusinessDetail(){
			try{
				const response = await apiRequest.getBusinessDetails();
				if(response.data.success == "true"){
					this.form = response.data.business;
				}else{
					this.form = {};
				}
			}catch(error){
				console.error('Error fetching business details:', error);
        		this.form = {};
			}
		}
	}
}

</script>