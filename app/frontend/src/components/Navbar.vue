

<template>
  <nav class="custom-navbar navbar navbar navbar-expand-md navbar-dark bg-dark" arial-label="Furni navigation bar">
    <div class="container">
      <router-link class="navbar-brand" to="/" v-if="displayBusinessName"> {{ displayBusinessName }} <span>.</span></router-link>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsFurni" aria-controls="navbarsFurni" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarsFurni">
        <ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
          <li class="nav-item ">
            <router-link class="nav-link" to="/">Home</router-link>
          </li>
          <li><router-link class="nav-link" to="/shop" >Shop</router-link></li>
          <li><router-link class="nav-link" to="/about" >About Us</router-link></li>
          <li><router-link class="nav-link" to="/services" >Services</router-link></li>
          <li><router-link class="nav-link" to="/blog" >Blog</router-link></li>
          <li><router-link class="nav-link" to="/contact_us" >Contact Us</router-link></li>
        </ul>

        <ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
          <li v-if="user?.first_name && user?.last_name">
            <router-link class="nav-link" to="/cart" >Cart 
              <span v-if="cartCount > 0" class="badge badge-pill badge-warning ml-2" style="background-color: #ffc107; color: black;">
                {{ cartCount }}
              </span>
            </router-link></li>
            <li v-if="user?.first_name && user?.last_name" class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="/images/user.svg" alt="User Icon"> 
                {{ user.first_name }} {{ user.last_name }}
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#" @click="logout">Logout</a></li>
              </ul>
            </li>

            <!-- Fallback for register link if the user is not logged in -->
            <li v-else>
              <router-link class="nav-link" to="/login">
                <img src="/images/user.svg" alt="User Icon">
              </router-link>
            </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
import apiRequest from '@/services/apiService';

export default {
  name: 'Navbar',
  data() {
    return {
      user: JSON.parse(localStorage.getItem('user')) || null,
      business_detail: null,
      cartCount: ''
    };
  },
  computed: {
    displayBusinessName() {
        return this.business_detail?.name || "HadiStore";
    },
  },
  watch : {
	$route(to, from) {
	    this.getBusinessDetail();
      const storedUser = localStorage.getItem('user');
      if (storedUser) {
        this.getCartCountItems();
      }
    }
  },
  mounted() { 
    this.getBusinessDetail();

    const storedUser = localStorage.getItem('user');
    if (storedUser) {
      this.user = JSON.parse(storedUser);
      this.getCartCountItems();
    }

    const navlink = document.querySelectorAll('.nav-link');

    navlink.forEach((navlinks) => {
      navlinks.addEventListener('click', function(){
        navlink.forEach((nav) => {
          const li = nav.closest('li');
          li.classList.remove('active');
        });
        const closestLi = navlinks.closest('li');
        closestLi.classList.add('active');
      })
    });

    const navbarBrand = document.querySelector('.navbar-brand');
    const navItem = document.querySelector('.nav-item');
    navbarBrand.addEventListener('click', function(){
      navlink.forEach((nav) =>{
        const li = nav.closest('li');
        li.classList.remove('active');
      })
      navItem.classList.add('active');
    });
  },
  methods: {

    async logout() {
      try {
        const response = await apiRequest.userLogout();

        if (response.data.status === 'success') {
          localStorage.removeItem('user');
          localStorage.removeItem('token');
          
          // Update the reactive data that triggers re-render
          this.user = null;

          this.$router.push('/login');

          // Show success message
          this.$swal({
            title: 'Logged Out!',
            text: response.data.message,
            icon: 'success',
            confirmButtonText: 'OK',
            customClass: {
                confirmButton: 'custom-confirm-button'
            }
          });
        } else {
          this.$swal({
            title: 'Error!',
            text: response.data.message,
            icon: 'error',
            confirmButtonText: 'OK',
            customClass: {
                confirmButton: 'custom-confirm-button'
            }
          });
        }
      } catch (error) {
        console.error("Error during logout:", error);
        this.$swal({
          title: 'Oops!',
          text: 'An error occurred while logging out.',
          icon: 'error',
          confirmButtonText: 'OK',
          customClass: {
                confirmButton: 'custom-confirm-button'
          }
        });
      }
    }, async getBusinessDetail(){
            try{
                const response = await apiRequest.getBusinessDetails();
                if(response.data.success == "true"){
                  this.business_detail = response.data.business;
                }else{
                  this.business_detail = {};
                }
            }catch(error){
                console.error('Error fetching business details:', error);
                this.business_detail = {};
            }
        },

        async getCartCountItems(){
          try{
              const userData = localStorage.getItem('user');
              const user = JSON.parse(userData);

              const formData = new FormData();
              formData.append('user_id', user.id);

              const response = await apiRequest.getCartCountItems(formData);
              if(response.data.success == "true"){
                  this.cartCount = response.data.count;
              }else{
                  console.error('Error fetching cart items:', response.data);
              }
          }catch(error){
            console.error('Error fetching cart items:', error);
          }
        }
  }
};
</script>
  