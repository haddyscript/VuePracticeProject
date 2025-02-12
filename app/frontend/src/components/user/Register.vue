<template>
    <section class="vh-100 gradient-custom">
  <div class="container py-5 h-100">
    <div class="row justify-content-center align-items-center h-100">
      <div class="col-12 col-lg-9 col-xl-7">
        <div class="card shadow-2-strong card-registration" style="border-radius: 15px;">
          <div class="card-body p-4 p-md-5">
            <h3 class="mb-4 pb-2 pb-md-0 mb-md-5">Registration Form</h3>
            <form @submit.prevent="submitForm">

              <div class="row">
                <div class="col-md-6 mb-4">

                  <div data-mdb-input-init class="form-outline">
                    <input type="text" v-model="form.first_name" id="firstName" class="form-control form-control-lg" />
                    <label class="form-label" for="firstName">First Name</label>
                  </div>

                </div>
                <div class="col-md-6 mb-4">

                  <div data-mdb-input-init class="form-outline">
                    <input type="text" v-model="form.last_name" id="lastName" class="form-control form-control-lg" />
                    <label class="form-label" for="lastName">Last Name</label>
                  </div>

                </div>
              </div>

              <div class="row">
                <div class="col-md-6 mb-4 d-flex align-items-center">

                  <div data-mdb-input-init class="form-outline datepicker w-100">
                    <input type="email" v-model="form.email" class="form-control form-control-lg" id="birthdayDate" required />
                    <label for="birthdayDate" class="form-label">Email</label>
                  </div>

                </div>
                <div class="col-md-6 mb-4">

                  <h6 class="mb-2 pb-1">Gender: </h6>

                  <div class="form-check form-check-inline">
                    <input class="form-check-input" v-model="form.gender" type="radio" name="inlineRadioOptions" id="femaleGender" value="Female" />
                    <label class="form-check-label" for="femaleGender">Female</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" v-model="form.gender" type="radio" name="inlineRadioOptions" id="maleGender" value="Male" />
                    <label class="form-check-label" for="maleGender">Male</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" v-model="form.gender" type="radio" name="inlineRadioOptions" id="otherGender" value="Other" />
                    <label class="form-check-label" for="otherGender">Other</label>
                  </div>

                </div>
              </div>

              <div class="row">
                <div class="col-md-6 mb-4 pb-2">

                  <div data-mdb-input-init class="form-outline">
                    <input type="password" v-model="form.password" id="emailAddress" class="form-control form-control-lg" />
                    <label class="form-label" for="emailAddress">Password</label>
                  </div>

                </div>
                <div class="col-md-6 mb-4 pb-2">

                  <div data-mdb-input-init class="form-outline">
                    <input type="password" v-model="form.confirm_password" id="phoneNumber" class="form-control form-control-lg" />
                    <label class="form-label" for="phoneNumber">Confirm Password</label>
                  </div>

                </div>
              </div>

              <div class="text-center mt-4">
                <p>Already have an account? 
                  <router-link class="text-success text-decoration-underline" to="/login">Log in here</router-link> .
                </p>
              </div>


              <div class="mt-4 pt-2">
                <input data-mdb-ripple-init class="btn btn-primary btn-lg" type="submit" value="Submit" />
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
</template>

<script>
import apiRequest from '@/services/apiService';
import showAlert from "@/services/swalAlert";

export default{
    data(){
        return {
            form:{
              first_name : '',
              last_name : '',
              email : '',
              password : '',
              confirm_password : '',
              role : 'user',
              gender : []
            }
        };
    },
    methods: {
      async submitForm(){
        try{
          const response = await apiRequest.userRegister(this.form);

          if(response.data.success == "true"){
            this.$swal({
                  title: 'Success!',
                  text: response.data.message,
                  icon: 'success',
                  confirmButtonText: 'OK',
                  customClass: {
                      confirmButton: 'custom-confirm-button'
                  }
              }).then(() => {
                  localStorage.setItem('token', response.data.token);
                  this.$router.push('/login');
              });
          }else{
            showAlert("error", "Oops!", response.data.message);
            console.log(response.data.message);
          }
        }catch(error){
          console.log("Failed to register user because " ,error);
        }
      }
    }
}
</script>

<style scoped>
.gradient-custom {
  background: #3b5d50;
  background: -webkit-linear-gradient(to bottom right, rgba(#3b5d50), rgba(#3b5d50));
  background: linear-gradient(to bottom right, rgba(#3b5d50), rgba(#3b5d50));
}

.card-registration {
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
}

.card-registration .form-outline {
  position: relative;
  margin-bottom: 1.5rem;
}

.card-registration .form-outline input {
  border: 1px solid #ced4da;
  border-radius: 8px;
  font-size: 1rem;
  padding: 0.8rem;
  transition: all 0.3s ease-in-out;
}

.card-registration .form-outline input:focus {
  border-color: #198754;
  box-shadow: 0 0 8px rgba(25, 135, 84, 0.4);
  outline: none;
}

.card-registration .form-outline input:focus + .form-label {
  font-weight: bold;
  color: #198754;
}

.card-registration .form-label {
  color: #6c757d;
  font-size: 0.9rem;
  transition: all 0.2s ease-in-out;
}

.card-registration .form-check-input {
  width: 17px;
  height: 17px;
}

.card-registration .form-check-label {
  font-size: 0.95rem;
  margin-left: 0.5rem;
  color: #495057;
}

.card-registration .btn-primary {
  background-color: #198754;
  border: none;
  font-size: 1rem;
  padding: 0.6rem 1.5rem;
  border-radius: 5px;
  transition: all 0.3s ease-in-out;
}

.card-registration .btn-primary:hover {
  background-color: #157347;
  box-shadow: 0 4px 10px rgba(21, 115, 71, 0.3);
}

.card-registration .btn-primary:active {
  background-color: #146c43;
  box-shadow: inset 0 3px 6px rgba(0, 0, 0, 0.2);
}

.card-registration .btn-primary:focus {
  outline: none;
  box-shadow: 0 0 8px rgba(25, 135, 84, 0.5);
}

/* Make the label green when the radio button is selected */
.card-registration .form-check-input:checked + .form-check-label {
  color: #198754;
  font-weight: bold;
}

/* Add a green border to the radio button when selected */
.card-registration .form-check-input:checked {
  border-color: #198754;
  background-color: #198754;
  box-shadow: 0 0 5px rgba(25, 135, 84, 0.4);
}
</style>
