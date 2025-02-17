<template>

<!-- Start Hero Section -->
<div class="hero">
				<div class="container">
					<div class="row justify-content-between">
						<div class="col-lg-5">
							<div class="intro-excerpt">
								<h1>Contact</h1>
								<p class="mb-4">Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate velit imperdiet dolor tempor tristique.</p>
								<p> <router-link class="btn btn-secondary me-2" to="/shop">Shop Now</router-link> <a href="#" class="btn btn-white-outline">Explore</a></p>
							</div>
						</div>
						<div class="col-lg-7">
							<div class="hero-img-wrap">
								<img src="/images/couch.png" class="img-fluid">
							</div>
						</div>
					</div>
				</div>
			</div>
		<!-- End Hero Section -->

    <div v-if="isLoading" class="loader-container">
			<div class="loader"></div>
		</div>

		<div v-else >
		
		<!-- Start Contact Form -->
		<div class="untree_co-section">
      <div class="container">

        <div class="block">
          <div class="row justify-content-center">


            <div class="col-md-8 col-lg-8 pb-4">


              <div class="row mb-5">
                <div class="col-lg-4">
                  <div  class="service no-shadow align-items-center link horizontal d-flex active" data-aos="fade-left" data-aos-delay="0">
                    <div class="service-icon color-1 mb-4">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                        <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                      </svg>
                    </div> <!-- /.icon -->
                    <div class="service-contents">
                      <p v-if="business_detail"> {{ business_detail.address }}, {{ business_detail.city }}, {{ business_detail.state }}, {{ business_detail.country }} - {{ business_detail.postal_code }} </p>
                    </div> <!-- /.service-contents-->
                  </div> <!-- /.service -->
                </div>

                <div class="col-lg-4">
                  <div  class="service no-shadow align-items-center link horizontal d-flex active" data-aos="fade-left" data-aos-delay="0">
                    <div class="service-icon color-1 mb-4">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                        <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555zM0 4.697v7.104l5.803-3.558L0 4.697zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757zm3.436-.586L16 11.801V4.697l-5.803 3.546z"/>
                      </svg>
                    </div> <!-- /.icon -->
                    <div class="service-contents">
                      <p v-if="business_detail">{{ business_detail.email }}</p>
                    </div> <!-- /.service-contents-->
                  </div> <!-- /.service -->
                </div>

                <div class="col-lg-4">
                  <div  class="service no-shadow align-items-center link horizontal d-flex active" data-aos="fade-left" data-aos-delay="0">
                    <div class="service-icon color-1 mb-4">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
                      </svg>
                    </div> <!-- /.icon -->
                    <div class="service-contents">
                      <p v-if="business_detail">{{ business_detail.phone_number }}</p>
                    </div> <!-- /.service-contents-->
                  </div> <!-- /.service -->
                </div>
              </div>

              <form>
                <div class="row">
                  <div class="col-6">
                    <div class="form-group">
                      <label class="text-black" for="fname">First name</label>
                      <input type="text" class="form-control" id="fname">
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group">
                      <label class="text-black" for="lname">Last name</label>
                      <input type="text" class="form-control" id="lname">
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="text-black" for="email">Email address</label>
                  <input type="email" class="form-control" id="email">
                </div>

                <div class="form-group mb-5">
                  <label class="text-black" for="message">Message</label>
                  <textarea name="" class="form-control" id="message" cols="30" rows="5"></textarea>
                </div>

                <button type="submit" class="btn btn-primary-hover-outline">Send Message</button>
              </form>

            </div>

          </div>

        </div>

      </div>
    </div>

    </div>

  <!-- End Contact Form -->
</template>

<script>
import apiRequest from '@/services/apiService';

export default {
  name: 'Navbar',
  data() {
    return {
      business_detail: null,
	    currentYear: '',
      isLoading: true,
    };
  },
  computed: {
    displayBusinessName() {
        return this.business_detail?.name || "HadiStore";
    },
	formatFoundedDate() {
      if (!this.business_detail || !this.business_detail.founded_year) return "N/A";
      return this.formatDate(this.business_detail.founded_year);
    }
  },
  watch : {
	$route(to, from) {
	    this.getBusinessDetail();
    }
  },
  mounted() { 
    this.getBusinessDetail();
    const currentDate = new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
    this.currentYear = currentDate;
    setTimeout(() => {
			this.isLoading = false; 
		}, 1000);
  },
  methods: {
		formatDate(dateString) {
			const date = new Date(dateString);
			if (isNaN(date.getTime())) return "Invalid Date"; 
			return date.toLocaleDateString("en-US", { year: "numeric", month: "long", day: "numeric" });
		},
		async getBusinessDetail(){
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
	}
}
</script>



<style>
.loader-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh; /* Full page */
  /* background-color: rgba(255, 255, 255, 0.8); */
  background-color: white;
  position: fixed;
  width: 100%;
  top: 0;
  left: 0;
  z-index: 999;
}

.loader {
  border: 5px solid #f3f3f3;
  border-top: 5px solid #3b5d50;
  border-radius: 50%;
  width: 50px;
  height: 50px;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

</style>