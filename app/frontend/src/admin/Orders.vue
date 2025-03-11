<template>
	<br><br>
    <div class="app-wrapper">
	    <div class="app-content pt-3 p-md-3 p-lg-4">
		    <div class="container-xl">
			    <div class="row g-3 mb-4 align-items-center justify-content-between">
				    <div class="col-auto">
			            <h1 class="app-page-title mb-0">Orders</h1>
				    </div>
				    <div class="col-auto">
					    <div class="page-utilities">
						    <div class="row g-2 justify-content-start justify-content-md-end align-items-center">
							    <div class="col-auto">
								    <form class="table-search-form row gx-1 align-items-center" @submit.prevent="searchOrders">
					                    <div class="col-auto">
					                        <input v-model="searchQuery" type="text" class="form-control search-orders" placeholder="Search">
					                    </div>
					                    <div class="col-auto">
					                        <button class="btn app-btn-secondary" @click.prevent="searchOrders">Search</button>
					                    </div>
					                </form>
							    </div>
							    <div class="col-auto">
								    <select v-model="selectedFilter" class="form-select w-auto">
										  <option value="All">All</option>
										  <option value="this_week">This Week</option>
										  <option value="this_month">This Month</option>
										  <option value="last_3_months">Last 3 Months</option>
										  <option value="Paid">Paid</option>
										  <option value="Pending">Pending</option>
										  <option value="Cancelled">Cancelled</option>
									</select>
							    </div>
							    <div class="col-auto">						    
								    <a class="btn app-btn-secondary" href="#">
									    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-download me-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
										  <path fill-rule="evenodd" d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
										  <path fill-rule="evenodd" d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
									    </svg>
									    Download CSV
									</a>
							    </div>
						    </div>
					    </div>
				    </div>
			    </div>

				<div class="tab-content">
			        <div class="tab-pane fade show active">
					    <div class="app-card app-card-orders-table shadow-sm mb-5">
						    <div class="app-card-body">
							    <div class="table-responsive">
							        <table class="table app-table-hover mb-0 text-left">
										<thead>
											<tr>
												<th class="cell">Order</th>
												<th class="cell">Product</th>
												<th class="cell">Customer</th>
												<th class="cell">Date</th>
												<th class="cell">Status</th>
												<th class="cell">Total</th>
												<th class="cell"></th>
											</tr>
										</thead>
										<tbody>
											<tr v-for="order in orderList" :key="order.id">
												<td class="cell">#{{ order.id }}</td>
												<td class="cell">
													<ul>
														<li v-for="product in order.product_names" :key="product">{{ product }}</li>
													</ul>
												</td>
												<td class="cell">{{ order.first_name + " " + order.last_name }}</td>
												<td class="cell">{{ order.created_at }}</td>
												<td class="cell">
													<span :class="badgeClass(order.is_paid)">
														{{ order.is_paid === 0 ? "Pending" : order.is_paid === 1 ? "Paid" : order.is_paid === 2 ? "Cancelled" : " " }}
													</span>
												</td>
												<td class="cell">{{ order.order_total }}</td>
												<td class="cell"><a class="btn-sm app-btn-secondary" href="#">View</a></td>
											</tr>
										</tbody>
									</table>
						        </div>
						    </div>
						</div>
						<nav class="app-pagination mt-5" v-if="pagination.total > 0">
							<ul class="pagination justify-content-center">
								<li class="page-item" :class="{ 'disabled': pagination.current_page === 1 }">
									<a class="page-link" href="#" @click.prevent="changePage(pagination.current_page - 1)">Previous</a>
								</li>
								<li 
									class="page-item" 
									v-for="page in pagination.last_page" 
									:key="page" 
									:class="{ 'active': pagination.current_page === page }">
									<a class="page-link" href="#" @click.prevent="changePage(page)">{{ page }}</a>
								</li>
								<li class="page-item" :class="{ 'disabled': pagination.current_page === pagination.last_page }">
									<a class="page-link" href="#" @click.prevent="changePage(pagination.current_page + 1)">Next</a>
								</li>
							</ul>
						</nav>
			        </div>
				</div>
		    </div>
	    </div>
    </div>
</template>

<script>
  import apiRequest from '@/services/apiService';
  import showAlert from '@/services/swalAlert';

  export default {
    data() {
        return {
            searchQuery: "",
            selectedFilter: "All",
            orderList: [],
			pagination: {}
        };
    },
	mounted(){
		this.searchOrders();
	},
	watch: {
        selectedFilter() {
            this.searchOrders(1, this.selectedFilter); 
        }
    },
    methods: {
        badgeClass(status) {
            switch (status) {
                case 1: return "badge bg-success";
                case 0: return "badge bg-warning";
                case 2: return "badge bg-danger";
                default: return "badge bg-secondary";
            }
        },
		changePage(page) {
            if (page >= 1 && page <= this.pagination.last_page) {
                this.searchOrders(page);
            }
        },
        async searchOrders(page = 1) { 
            try {
                const formData = {
                    search: {
                        order_id: this.searchQuery || null,  
                        this_week: this.selectedFilter === "this_week" ? true : null,
                        this_month: this.selectedFilter === "this_month" ? true : null,
                        last_3_months: this.selectedFilter === "last_3_months" ? true : null,
                        for_paid: this.selectedFilter === "Paid" ? true : null,
                        for_pending: this.selectedFilter === "Pending" ? true : null,
                        for_cancelled: this.selectedFilter === "Cancelled" ? true : null
                    },
                    status: {
                        page: page 
                    }
                };

                const response = await apiRequest.getOrderList(formData);

                if (response.data.success) {
                    this.orderList = response.data.order_products;
                    this.pagination = response.data.pagination;
                    console.log('Updated Order List:', this.orderList);
                }
            } catch (e) {    
                console.error(e);
            }
        }
    }
};

</script>
