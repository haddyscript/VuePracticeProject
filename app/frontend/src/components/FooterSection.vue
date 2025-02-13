<template>
    <!-- Start Footer Section -->
		<footer class="footer-section">
			<div class="container relative">

				<div class="sofa-img">
					<img src="/images/sofa.png" alt="/Image" class="img-fluid">
				</div>

				<div class="row">
					<div class="col-lg-8">
						<div class="subscription-form">
							<h3 class="d-flex align-items-center"><span class="me-1"><img src="/images/envelope-outline.svg" alt="Image" class="img-fluid"></span><span>Subscribe to Newsletter</span></h3>

							<form action="#" class="row g-3">
								<div class="col-auto">
									<input type="text" class="form-control" placeholder="Enter your name">
								</div>
								<div class="col-auto">
									<input type="email" class="form-control" placeholder="Enter your email">
								</div>
								<div class="col-auto">
									<button class="btn btn-primary">
										<span class="fa fa-paper-plane"></span>
									</button>
								</div>
							</form>

						</div>
					</div>
				</div>

				<div class="row g-5 mb-5">
					<div class="col-lg-4">
						<div class="mb-4 footer-logo-wrap"><a href="#" class="footer-logo" v-if="displayBusinessName"> {{ displayBusinessName }}<span>.</span></a></div>
						<p class="mb-4" v-if="business_detail">{{ business_detail.description }}</p>
						<ul class="list-unstyled custom-social">
							<li><a href="#"><span class="fa fa-brands fa-facebook-f"></span></a></li>
							<li><a href="#"><span class="fa fa-brands fa-twitter"></span></a></li>
							<li><a href="#"><span class="fa fa-brands fa-instagram"></span></a></li>
							<li><a href="#"><span class="fa fa-brands fa-linkedin"></span></a></li>
						</ul>
					</div>

					<div class="col-lg-8" v-if="business_detail">
						<div class="row links-wrap">
							<div class="col-6 col-sm-6 col-md-3">
								<ul class="list-unstyled">
									<li><router-link to="/about">About us</router-link></li>
									<li><router-link to="/services">Services</router-link></li>
									<li><router-link to="/blog">Blog</router-link></li>
									<li><router-link to="/contact_us">Contact us</router-link></li>
								</ul>
							</div>

							<div class="col-6 col-sm-6 col-md-3">
								<ul class="list-unstyled">
									<li><strong>Owner:</strong> {{ business_detail.owner_name }}</li>
									<li><strong>Contact Person:</strong> {{ business_detail.contact_name }}</li>
									<li><strong>Email:</strong> <a :href="'mailto:' + business_detail.email">{{ business_detail.email }}</a></li>	
								</ul>
							</div>

							<div class="col-6 col-sm-6 col-md-3">
								<ul class="list-unstyled">
									<li><strong>Phone:</strong> <a :href="'tel:' + business_detail.phone_number">{{ business_detail.phone_number }}</a></li>
									<li><strong>Founded:</strong> {{ formatFoundedDate }}</li>
								</ul>
							</div>

							<div class="col-6 col-sm-6 col-md-3">
								<ul class="list-unstyled">
									<li><strong>Address:</strong> {{ business_detail.address }}, {{ business_detail.city }}, {{ business_detail.state }}, {{ business_detail.country }} - {{ business_detail.postal_code }}</li>
									<li><strong>Business Hours:</strong> {{ business_detail.business_hours }}</li>
								</ul>
							</div>
						</div>
					</div>

				</div>

				<div class="border-top copyright">
					<div class="row pt-4">
						<div class="col-lg-6">
                            <p class="mb-2 text-center text-lg-start">
                                Copyright &copy; {{ currentYear }}. All Rights Reserved.
                            </p>
						</div>

						<div class="col-lg-6 text-center text-lg-end">
							<ul class="list-unstyled d-inline-flex ms-auto">
								<li class="me-4"><a href="#">Terms &amp; Conditions</a></li>
								<li><a href="#">Privacy Policy</a></li>
							</ul>
						</div>

					</div>
				</div>

			</div>
		</footer>
		<!-- End Footer Section -->	
</template>


<script>
import apiRequest from '@/services/apiService';

export default {
  name: 'Navbar',
  data() {
    return {
      business_detail: null,
	  currentYear: ''
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
				  console.log(this.business_detail);
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