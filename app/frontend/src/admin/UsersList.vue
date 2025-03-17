<template>
	<br><br>
    <div class="app-wrapper">
	    <div class="app-content pt-3 p-md-3 p-lg-4">
		    <div class="container-xl">
			    <div class="row g-3 mb-4 align-items-center justify-content-between">
				    <div class="col-auto">
			            <h1 class="app-page-title mb-0">Users</h1>
				    </div>
				    <div class="col-auto">
					    <div class="page-utilities">
						    <div class="row g-2 justify-content-start justify-content-md-end align-items-center">
							    <div class="col-auto">
								    <form class="table-search-form row gx-1 align-items-center" @submit.prevent="adminGetUserList">
					                    <div class="col-auto">
					                        <input v-model="searchQuery" type="text" class="form-control search-orders" placeholder="Search User ID">
					                    </div>
					                    <div class="col-auto">
					                        <button class="btn app-btn-secondary" @click.prevent="adminGetUserList(page = 1)">Search</button>
					                    </div>
					                </form>
							    </div>
							    <div class="col-auto">
									<select v-model="selectedFilter" class="form-select w-auto">
										<option value="All">All Users</option>
										<option value="active">Active Users</option>
										<option value="inactive">Inactive Users</option>
										<option value="verified">Verified Users</option>
										<option value="unverified">Unverified Users</option>
										<option value="male">Male Users</option>
										<option value="female">Female Users</option>
										<option value="recent">Recently Registered</option>
										<option value="this_week">Registered This Week</option>
										<option value="this_month">Registered This Month</option>
										<option value="last_3_months">Registered Last 3 Months</option>
									</select>
								</div>
							    <div class="col-auto">						    
								    <a class="btn app-btn-secondary" href="#" @click="downloadCSV">
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
												<th class="cell">ID</th>
												<th class="cell">First Name</th>
												<th class="cell">Last Name</th>
												<th class="cell">Email</th>
												<th class="cell">Gender</th>
												<th class="cell">Date of Birth</th>
												<th class="cell">Phone Number</th>
												<th class="cell">Address</th>
												<th class="cell">City</th>
												<th class="cell">State</th>
												<th class="cell">Country</th>
												<th class="cell">Postal Code</th>
												<th class="cell">Active</th>
												<th class="cell">Verified</th>
												<th class="cell">Created At</th>
												<th class="cell">Updated At</th>
												<th class="cell">Actions</th>
											</tr>
										</thead>
										<tbody>
											<tr v-for="userlist in userslists" :key="userlist.id">
												<td class="cell"> {{ userlist.id }}</td>	
												<td class="cell"> {{ userlist.first_name }} </td>
												<td class="cell"> {{ userlist.last_name }} </td>
												<td class="cell"> {{ userlist.email }} </td>
												<td class="cell"> {{ userlist.gender === 'male' ? 'M' : userlist.gender === 'female' ? 'F' : userlist.gender === 'other' ? 'O' : 'N/A'}} </td>
												<td class="cell"> {{ userlist.date_of_birth }} </td>
												<td class="cell"> {{ userlist.phone_number }} </td>
												<td class="cell"> {{ userlist.address }} </td>
												<td class="cell"> {{ userlist.city }} </td>
												<td class="cell"> {{ userlist.state }} </td>
												<td class="cell"> {{ userlist.country }} </td>
												<td class="cell"> {{ userlist.postal_code }} </td>
												<td class="cell"> {{ userlist.is_active == 1 ? 'Yes' : 'No'}} </td>
												<td class="cell"> {{ userlist.verified == 1 ? 'Yes' : 'No'}} </td>
												<td class="cell"> {{ formatDate(userlist.created_at)}} </td>
												<td class="cell"> {{ formatDate(userlist.updated_at)}} </td>
												<td v-if="userlist.is_active === 1" class="cell">
													<a class="btn btn-sm app-btn-secondary text-danger" @click="openModal(userlist.id, 'deactivate')">Deactivate</a>
												</td>
												<td v-if="userlist.is_active === 0" class="cell">
													<a class="btn btn-sm app-btn-secondary text-danger" @click="openModal(userlist.id, 'activate')">Activate</a>
												</td>
											</tr>
											</tbody>
									</table>
						        </div>
						    </div>
						</div>
						<p class="text-center">Total Users: {{ total_count }}</p>
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
		<!-- Confirmation Modal -->
		<div class="modal fade" id="confirmCancelModal" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Confirm {{ this.action === 'activate' ? 'Activation' : this.action === 'deactivate' ? 'Deactivation' : ''}}</h5>
					<button type="button" class="btn-close" @click="closeModal"></button>
				</div>
				<div class="modal-body">
					Are you sure you want to cancel this order?
				</div>
				<div class="modal-footer">
					<button class="btn btn-secondary" @click="closeModal">No</button>
					<button class="btn btn-danger" @click="confirmCancel(user_id, action)">Yes proceed</button>
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
            userslists: [],
			pagination: {},
			selectedUserId : null,
			action :'',
			total_count: 0
        };
    },
	mounted(){
		this.adminGetUserList();
	},
	watch: {
        selectedFilter() {
            this.adminGetUserList(1, this.selectedFilter); 
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
                this.adminGetUserList(page);
            }
        },
        async adminGetUserList(page) { 
            try {
                const formData = {
                    search: {
                        user_id: this.searchQuery || null,  
						active: this.selectedFilter === "active" ? true : null,
						inactive: this.selectedFilter === "inactive" ? true : null,
						verified: this.selectedFilter === "verified" ? true : null,
						unverified: this.selectedFilter === "unverified" ? true : null,
						male: this.selectedFilter === "male" ? true : null,
						female: this.selectedFilter === "female" ? true : null,
						recent: this.selectedFilter === "recent" ? true : null,
						this_week: this.selectedFilter === "this_week" ? true : null,
						this_month: this.selectedFilter === "this_month" ? true : null,
						last_3_months: this.selectedFilter === "last_3_months" ? true : null
                    },
                    status: {
                        page: page 
                    }
                };

                const response = await apiRequest.adminGetUserList(formData);

                if (response.data.success) {
                    this.userslists = response.data.userslists;
                    this.pagination = response.data.pagination;
					this.total_count = response.data.total_count;
                    console.log('Updated User List:', this.userslists);
                }
            } catch (e) {    
                console.error(e);
            }
        },
		formatDate(dateString) {
			if (!dateString) return '-';
				const date = new Date(dateString);
				return date.toLocaleString('en-US', {
					year: 'numeric',
					month: 'long',
					day: 'numeric',
					hour: '2-digit',
					minute: '2-digit',
					second: '2-digit',
					hour12: true
				}
			);
		},
		downloadCSV() {
			if (!this.userslists || this.userslists.length === 0) {
				alert("No data available for download.");
				return;
			}

			const headers = [
				"ID", "First Name", "Last Name", "Email", "Gender", "Date of Birth",
				"Phone Number", "Address", "City", "State", "Country", "Postal Code",
				"Active", "Verified", "Created At", "Updated At"
			];

			const rows = this.userslists.map(user => [
				user.id,
				user.first_name || "N/A",
				user.last_name || "N/A",
				user.email || "N/A",
				user.gender === "male" ? "M" : user.gender === "female" ? "F" : user.gender === "other" ? "O" : "N/A",
				user.date_of_birth || "N/A",
				user.phone_number || "N/A",
				user.address || "N/A",
				user.city || "N/A",
				user.state || "N/A",
				user.country || "N/A",
				user.postal_code || "N/A",
				user.is_active == 1 ? "Yes" : "No",
				user.verified == 1 ? "Yes" : "No",
				this.formatDate(user.created_at),
				this.formatDate(user.updated_at)
			]);

			const csvContent = [headers, ...rows].map(e => e.join(",")).join("\n");

			const blob = new Blob([csvContent], { type: "text/csv" });
			const link = document.createElement("a");
			link.href = URL.createObjectURL(blob);
			link.download = "users_list.csv";
			document.body.appendChild(link);
			link.click();
			document.body.removeChild(link);
		},
		openModal(orderId, action) {
			this.action = action;
			this.selectedUserId = orderId; 
			const modal = new bootstrap.Modal(document.getElementById("confirmCancelModal"));
			modal.show();
		},
		closeModal() {
			const modalEl = document.getElementById("confirmCancelModal");
			const modal = bootstrap.Modal.getInstance(modalEl);
			this.selectedUserId = null;
			this.action = null;
			modal.hide();
		},
		async confirmCancel() {
			if (!this.selectedUserId) return;
			try{
				const formData = new FormData();
				formData.append("user_id", this.selectedUserId);
				formData.append('action', this.action)
				const response = await apiRequest.adminDeactivateUser(formData);

				if(response.data.success == "true"){
					showAlert("success", "Done!", response.data.message);
					this.adminGetUserList();
				}else{
					showAlert("error", "Oops!", response.data.message);
				}
			}catch(e){
				error.log(e);
			}

			this.closeModal();
		}
    }
};

</script>
