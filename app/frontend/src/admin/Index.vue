<template>
        <div class="app-wrapper">
	    
	    <div class="app-content pt-3 p-md-3 p-lg-4">
		    <div class="container-xl">
			    
			    <h1 class="app-page-title">Overview</h1>
			    
			    <div class="app-card alert alert-dismissible shadow-sm mb-4 border-left-decoration" role="alert">
				    <div class="inner">
					    <div class="app-card-body p-3 p-lg-4">
						    <h3 class="mb-3">Welcome Back, Admin!</h3>
						    <div class="row gx-5 gy-3">
						        <div class="col-12 col-lg-9">
							        
							        <div>Lead with confidence and make impactful decisions. Every action you take shapes the future—stay focused, stay inspired, and keep driving success. 🚀</div>
							    </div><!--//col-->

						    </div><!--//row-->
						    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
					    </div><!--//app-card-body-->
					    
				    </div><!--//inner-->
			    </div><!--//app-card-->
				    
			    <div class="row g-4 mb-4">
				    <div class="col-6 col-lg-3">
					    <div class="app-card app-card-stat shadow-sm h-100">
						    <div class="app-card-body p-3 p-lg-4">
							    <h4 class="stats-type mb-1">Total Sales</h4>
							    <div class="stats-figure">₱{{ animatedTotalSales }}</div>
							    <div class="stats-meta text-success">
								    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-up" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z"/>
</svg> 20%</div>
						    </div><!--//app-card-body-->
						    <a class="app-card-link-mask" href="#"></a>
					    </div><!--//app-card-->
				    </div><!--//col-->
				    
				    <div class="col-6 col-lg-3">
					    <div class="app-card app-card-stat shadow-sm h-100">
						    <div class="app-card-body p-3 p-lg-4">
							    <h4 class="stats-type mb-1">Cancelled</h4>
							    <div class="stats-figure">₱{{ animatedTotalCancelled }}</div>
							    <div class="stats-meta text-success">
								    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-down" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1z"/>
</svg> 5% </div>
						    </div><!--//app-card-body-->
						    <a class="app-card-link-mask" href="#"></a>
					    </div><!--//app-card-->
				    </div><!--//col-->
				    <div class="col-6 col-lg-3">
					    <div class="app-card app-card-stat shadow-sm h-100">
						    <div class="app-card-body p-3 p-lg-4">
							    <h4 class="stats-type mb-1">Products</h4>
							    <div class="stats-figure">{{ animatedTotalProducts }}</div>
							    <div class="stats-meta">
								    Open</div>
						    </div><!--//app-card-body-->
						    <router-link to="/admin/product" class="app-card-link-mask" ></router-link>
					    </div><!--//app-card-->
				    </div><!--//col-->
				    <div class="col-6 col-lg-3">
					    <div class="app-card app-card-stat shadow-sm h-100">
						    <div class="app-card-body p-3 p-lg-4">
							    <h4 class="stats-type mb-1">Invoices</h4>
							    <div class="stats-figure">{{ animatedInVoices }}</div>
							    <div class="stats-meta">New</div>
						    </div><!--//app-card-body-->
						    <router-link to="/admin/orders" class="app-card-link-mask" href="#"></router-link>
					    </div><!--//app-card-->
				    </div><!--//col-->
			    </div><!--//row-->
			    <div class="row g-4 mb-4">
			        <div class="col-12 col-lg-6">
						<div class="app-card app-card-chart h-100 shadow-sm">
						<div class="app-card-header p-3">
							<div class="row justify-content-between align-items-center">
							<div class="col-auto">
								<h4 class="app-card-title">Line Chart of Sales</h4>
							</div>
							<div class="col-auto">
								<div class="card-header-action">
								<a href="charts.html">More charts</a>
								</div>
							</div>
							</div>
						</div>
						<div class="app-card-body p-3 p-lg-4">
							<div class="chart-container" style="height: 300px;">
							<Line :data="chartData" :options="chartOptions" />
							</div>
						</div>
						</div>
					</div>
			        <div class="col-12 col-lg-6">
						<div class="app-card app-card-chart h-100 shadow-sm">
							<div class="app-card-header p-3">
							<div class="row justify-content-between align-items-center">
								<div class="col-auto">
								<h4 class="app-card-title">Bar Chart of Sales</h4>
								</div>
								<div class="col-auto">
								<div class="card-header-action">
									<a href="charts.html">More charts</a>
								</div>
								</div>
							</div>
							</div>
							<div class="app-card-body p-3 p-lg-4">
							<div class="chart-container" style="height: 300px;">
								<Bar :data="chartData" :options="chartOptions" />
							</div>
							</div>
						</div>
					</div>
			        
			    </div><!--//row-->
			    <div class="row g-4 mb-4">
				    <div class="col-12 col-lg-6">
				        <div class="app-card app-card-progress-list h-100 shadow-sm">
					        <div class="app-card-header p-3">
						        <div class="row justify-content-between align-items-center">
							        <div class="col-auto">
						                <h4 class="app-card-title">Progress</h4>
							        </div><!--//col-->
							        <div class="col-auto">
							        </div><!--//col-->
						        </div><!--//row-->
					        </div><!--//app-card-header-->
					        <div class="app-card-body">
							    <div class="item p-3">
								    <div class="row align-items-center">
									    <div class="col">
										    <div class="title mb-1 ">User Growth Progress</div>
										    <div class="progress">
												<div class="progress-bar bg-success" role="progressbar"
													:style="{ width: userGrowth + '%' }"
													:aria-valuenow="userGrowth"
													aria-valuemin="0" aria-valuemax="100">
												</div>
											</div>
									    </div><!--//col-->
									    <div class="col-auto">
										    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-chevron-right" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
</svg>
									    </div><!--//col-->
								    </div><!--//row-->
								    <a class="item-link-mask" href="#"></a>
							    </div><!--//item-->
							    
							    
							     <div class="item p-3">
								    <div class="row align-items-center">
									    <div class="col">
										    <div class="title mb-1 ">Order Fullfillment Rate</div>
										    <div class="progress">
												<div class="progress-bar bg-success" role="progressbar"
													:style="{ width: orderFulfillmentRate + '%' }"
													:aria-valuenow="orderFulfillmentRate"
													aria-valuemin="0" aria-valuemax="100">
												</div>
											</div>
									    </div><!--//col-->
									    <div class="col-auto">
										    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-chevron-right" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
</svg>
									    </div><!--//col-->
								    </div><!--//row-->
								    <a class="item-link-mask" href="#"></a>
							    </div><!--//item-->
							    
							    <div class="item p-3">
								    <div class="row align-items-center">
									    <div class="col">
										    <div class="title mb-1 ">New Order Pending Rate</div>
										    <div class="progress">
												<div class="progress-bar bg-success" role="progressbar"
													:style="{ width: newOrderPendingRate + '%' }"
													:aria-valuenow="newOrderPendingRate"
													aria-valuemin="0" aria-valuemax="100">
												</div>
											</div>
									    </div><!--//col-->
									    <div class="col-auto">
										    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-chevron-right" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
</svg>
									    </div><!--//col-->
								    </div><!--//row-->
								    <a class="item-link-mask" href="#"></a>
							    </div><!--//item-->
							    
							    <div class="item p-3">
								    <div class="row align-items-center">
									    <div class="col">
										    <div class="title mb-1 ">Sales Growth this month</div>
										    <div class="progress">
												<div class="progress-bar bg-success" role="progressbar"
													:style="{ width: salesGrowthMonth + '%' }"
													:aria-valuenow="salesGrowthMonth"
													aria-valuemin="0" aria-valuemax="100">
												</div><div class="progress-bar bg-success" role="progressbar" style="width: 52%;" aria-valuenow="52" aria-valuemin="0" aria-valuemax="100"></div>
											</div>
									    </div><!--//col-->
									    <div class="col-auto">
										    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-chevron-right" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
</svg>
									    </div><!--//col-->
								    </div><!--//row-->
								    <a class="item-link-mask" href="#"></a>
							    </div><!--//item-->

								<div class="item p-3">
								    <div class="row align-items-center">
									    <div class="col">
										    <div class="title mb-1 ">Sales Growth this week</div>
										    <div class="progress">
												<div class="progress-bar bg-success" role="progressbar"
													:style="{ width: salesGrowthWeek + '%' }"
													:aria-valuenow="salesGrowthWeek"
													aria-valuemin="0" aria-valuemax="100">
												</div>
											</div>
									    </div><!--//col-->
									    <div class="col-auto">
										    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-chevron-right" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
</svg>
									    </div><!--//col-->
								    </div><!--//row-->
								    <a class="item-link-mask" href="#"></a>
							    </div><!--//item-->
		
					        </div><!--//app-card-body-->
				        </div><!--//app-card-->
			        </div><!--//col-->
			        <div class="col-12 col-lg-6">
				        <div class="app-card app-card-stats-table h-100 shadow-sm">
					        <div class="app-card-header p-3">
						        <div class="row justify-content-between align-items-center">
							        <div class="col-auto">
						                <h4 class="app-card-title">Stats List</h4>
							        </div><!--//col-->
							        <div class="col-auto">
								        <div class="card-header-action">
									        <a href="#">View report</a>
								        </div><!--//card-header-actions-->
							        </div><!--//col-->
						        </div><!--//row-->
					        </div><!--//app-card-header-->
					        <div class="app-card-body p-3 p-lg-4">
						        <div class="table-responsive">
							        <table class="table table-borderless mb-0">
										<thead>
											<tr>
												<th class="meta">Metric</th>
												<th class="meta stat-cell">Value</th>
												<th class="meta stat-cell">Change (%)</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td><router-link to="/admin">Total Active User</router-link></td>
												<td class="stat-cell">{{ totalActiveUsers }}</td>
												<td class="stat-cell">
													<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-up text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
									  <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z"/>
									</svg> 
									            </td>
											</tr>
											<tr>
												<td><a href="#">Total Registered User</a></td>
												<td class="stat-cell">{{ totalRegisteredUsers }}</td>
												<td class="stat-cell"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-up text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
									  <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z"/>
									</svg> 	</td>
											</tr>
											<tr>
												<td><router-link to="/admin/product">Total Products Quantity</router-link></td>
												<td class="stat-cell">{{ totalProductsQuantity }}</td>
												<td class="stat-cell">
													<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-up text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
									  <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z"/>
									</svg> 
											    </td>
											</tr>
											<tr>
												<td><router-link to="/admin/product">Total ProductsOut of Stock </router-link></td>
												<td class="stat-cell">{{ totalOutOfStockProducts }}</td>
												<td class="stat-cell">
													<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-up text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
									  <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z"/>
									</svg> 
												</td>
											</tr>
											<tr>
												<td><router-link to="/admin/orders">Most Used Method of Payment </router-link></td>
												<td class="stat-cell">{{ getMethodOfPaymentMostUsed }}</td>
												<td class="stat-cell"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-up text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
									  <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z"/>
									</svg> </td>
											</tr>
											<tr>
												<td><router-link to="/admin/orders">Average Order Value</router-link></td>
												<td class="stat-cell">{{ averageOrderValue }}</td>
												<td class="stat-cell"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-up text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
									  <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z"/>
									</svg> </td>
											</tr>
											<tr>
												<td><router-link to="/admin/orders">Total Revenue </router-link></td>
												<td class="stat-cell">{{ revenue }}</td>
												<td class="stat-cell"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-up text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
									  <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z"/>
									</svg> </td>
											</tr>
											<tr>
												<td><router-link to="/admin/orders">Total Orders </router-link></td>
												<td class="stat-cell">{{ inVoices }}</td>
												<td class="stat-cell"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-up text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
									  <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z"/>
									</svg> </td>
											</tr>
										</tbody>
									</table>
						        </div><!--//table-responsive-->
					        </div><!--//app-card-body-->
				        </div><!--//app-card-->
			        </div><!--//col-->
			    </div><!--//row-->
			    <div class="row g-4 mb-4">
				    <div class="col-12 col-lg-4">
					    <div class="app-card app-card-basic d-flex flex-column align-items-start shadow-sm">
						    <div class="app-card-header p-3 border-bottom-0">
						        <div class="row align-items-center gx-3">
							        <div class="col-auto">
								        <div class="app-icon-holder">
										    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-receipt" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M1.92.506a.5.5 0 0 1 .434.14L3 1.293l.646-.647a.5.5 0 0 1 .708 0L5 1.293l.646-.647a.5.5 0 0 1 .708 0L7 1.293l.646-.647a.5.5 0 0 1 .708 0L9 1.293l.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .801.13l.5 1A.5.5 0 0 1 15 2v12a.5.5 0 0 1-.053.224l-.5 1a.5.5 0 0 1-.8.13L13 14.707l-.646.647a.5.5 0 0 1-.708 0L11 14.707l-.646.647a.5.5 0 0 1-.708 0L9 14.707l-.646.647a.5.5 0 0 1-.708 0L7 14.707l-.646.647a.5.5 0 0 1-.708 0L5 14.707l-.646.647a.5.5 0 0 1-.708 0L3 14.707l-.646.647a.5.5 0 0 1-.801-.13l-.5-1A.5.5 0 0 1 1 14V2a.5.5 0 0 1 .053-.224l.5-1a.5.5 0 0 1 .367-.27zm.217 1.338L2 2.118v11.764l.137.274.51-.51a.5.5 0 0 1 .707 0l.646.647.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.509.509.137-.274V2.118l-.137-.274-.51.51a.5.5 0 0 1-.707 0L12 1.707l-.646.647a.5.5 0 0 1-.708 0L10 1.707l-.646.647a.5.5 0 0 1-.708 0L8 1.707l-.646.647a.5.5 0 0 1-.708 0L6 1.707l-.646.647a.5.5 0 0 1-.708 0L4 1.707l-.646.647a.5.5 0 0 1-.708 0l-.509-.51z"/>
  <path fill-rule="evenodd" d="M3 4.5a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm8-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5z"/>
</svg>
									    </div><!--//icon-holder-->
						                
							        </div><!--//col-->
							        <div class="col-auto">
								        <h4 class="app-card-title">Invoices</h4>
							        </div><!--//col-->
						        </div><!--//row-->
						    </div><!--//app-card-header-->
						    <div class="app-card-body px-4">
								<div class="intro">
									<table class="table table-borderless mb-0">
										<thead>
											<tr>
												<th style="font-size: 15px;">Reference</th>
												<th style="font-size: 15px;">Amount (₱)</th>
												<th style="font-size: 15px;">MOD</th>
												<th style="font-size: 15px;">Date</th>
											</tr>
										</thead>
										<tbody>
											<tr v-for="invoice in inVoiceList" :key="invoice.id">
												<td style="font-size: 13px;">#{{ invoice.id }}</td>
												<td style="font-size: 13px;">₱{{ invoice.order_total }}</td>
												<td style="font-size: 13px;">{{ invoice.mode_of_payment == 1 ? 'Cash' : invoice.mode_of_payment == 2 ? 'Bank' : invoice.mode_of_payment == 3 ? 'Pay Pal' : invoice.mode_of_payment == 4 ? 'Gcash' : ' ' }}</td>
												<td style="font-size: 13px;">{{ new Date(invoice.created_at).toLocaleDateString() }}</td>
											</tr>
										</tbody>
									</table>
								</div>
						    </div><!--//app-card-body-->
						    <div class="app-card-footer p-4 mt-auto">
							   <router-link to="/admin/orders" class="btn app-btn-secondary" href="#">more</router-link>
						    </div><!--//app-card-footer-->
						</div><!--//app-card-->
				    </div><!--//col-->
				    <div class="col-12 col-lg-4">
					    <div class="app-card app-card-basic d-flex flex-column align-items-start shadow-sm">
						    <div class="app-card-header p-3 border-bottom-0">
						        <div class="row align-items-center gx-3">
							        <div class="col-auto">
								        <div class="app-icon-holder">
											<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-receipt" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M1.92.506a.5.5 0 0 1 .434.14L3 1.293l.646-.647a.5.5 0 0 1 .708 0L5 1.293l.646-.647a.5.5 0 0 1 .708 0L7 1.293l.646-.647a.5.5 0 0 1 .708 0L9 1.293l.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .801.13l.5 1A.5.5 0 0 1 15 2v12a.5.5 0 0 1-.053.224l-.5 1a.5.5 0 0 1-.8.13L13 14.707l-.646.647a.5.5 0 0 1-.708 0L11 14.707l-.646.647a.5.5 0 0 1-.708 0L9 14.707l-.646.647a.5.5 0 0 1-.708 0L7 14.707l-.646.647a.5.5 0 0 1-.708 0L5 14.707l-.646.647a.5.5 0 0 1-.708 0L3 14.707l-.646.647a.5.5 0 0 1-.801-.13l-.5-1A.5.5 0 0 1 1 14V2a.5.5 0 0 1 .053-.224l.5-1a.5.5 0 0 1 .367-.27zm.217 1.338L2 2.118v11.764l.137.274.51-.51a.5.5 0 0 1 .707 0l.646.647.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.509.509.137-.274V2.118l-.137-.274-.51.51a.5.5 0 0 1-.707 0L12 1.707l-.646.647a.5.5 0 0 1-.708 0L10 1.707l-.646.647a.5.5 0 0 1-.708 0L8 1.707l-.646.647a.5.5 0 0 1-.708 0L6 1.707l-.646.647a.5.5 0 0 1-.708 0L4 1.707l-.646.647a.5.5 0 0 1-.708 0l-.509-.51z"/>
  <path fill-rule="evenodd" d="M3 4.5a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm8-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5z"/>
</svg>
									    </div><!--//icon-holder-->
						                
							        </div><!--//col-->
							        <div class="col-auto">
								        <h4 class="app-card-title">Product List</h4>
							        </div><!--//col-->
						        </div><!--//row-->
						    </div><!--//app-card-header-->
						    <div class="app-card-body px-4">
							    
							    <div class="intro">
									<table class="table table-borderless mb-0">
										<thead>
											<tr>
												<th style="font-size: 15px;">Name</th>
												<th style="font-size: 15px;">Price (₱)</th>
												<th style="font-size: 15px;">QTY</th>
											</tr>
										</thead>
										<tbody>
											<tr v-for="product in productLists" :key="product.id">
												<td style="font-size: 13px;">{{ product.name }}</td>
												<td style="font-size: 13px;">₱{{ product.price }}</td>
												<td style="font-size: 13px;">{{ product.stock_quantity }} pcs</td>
											</tr>
										</tbody>
									</table>
								</div>
						    </div><!--//app-card-body-->
						    <div class="app-card-footer p-4 mt-auto">
							   <router-link to="/admin/product" class="btn app-btn-secondary" href="#">more</router-link>
						    </div><!--//app-card-footer-->
						</div><!--//app-card-->
				    </div><!--//col-->
				    <div class="col-12 col-lg-4">
					    <div class="app-card app-card-basic d-flex flex-column align-items-start shadow-sm">
						    <div class="app-card-header p-3 border-bottom-0">
						        <div class="row align-items-center gx-3">
							        <div class="col-auto">
								        <div class="app-icon-holder">
											<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-receipt" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M1.92.506a.5.5 0 0 1 .434.14L3 1.293l.646-.647a.5.5 0 0 1 .708 0L5 1.293l.646-.647a.5.5 0 0 1 .708 0L7 1.293l.646-.647a.5.5 0 0 1 .708 0L9 1.293l.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .801.13l.5 1A.5.5 0 0 1 15 2v12a.5.5 0 0 1-.053.224l-.5 1a.5.5 0 0 1-.8.13L13 14.707l-.646.647a.5.5 0 0 1-.708 0L11 14.707l-.646.647a.5.5 0 0 1-.708 0L9 14.707l-.646.647a.5.5 0 0 1-.708 0L7 14.707l-.646.647a.5.5 0 0 1-.708 0L5 14.707l-.646.647a.5.5 0 0 1-.708 0L3 14.707l-.646.647a.5.5 0 0 1-.801-.13l-.5-1A.5.5 0 0 1 1 14V2a.5.5 0 0 1 .053-.224l.5-1a.5.5 0 0 1 .367-.27zm.217 1.338L2 2.118v11.764l.137.274.51-.51a.5.5 0 0 1 .707 0l.646.647.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.509.509.137-.274V2.118l-.137-.274-.51.51a.5.5 0 0 1-.707 0L12 1.707l-.646.647a.5.5 0 0 1-.708 0L10 1.707l-.646.647a.5.5 0 0 1-.708 0L8 1.707l-.646.647a.5.5 0 0 1-.708 0L6 1.707l-.646.647a.5.5 0 0 1-.708 0L4 1.707l-.646.647a.5.5 0 0 1-.708 0l-.509-.51z"/>
  <path fill-rule="evenodd" d="M3 4.5a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm8-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5z"/>
</svg>
									    </div><!--//icon-holder-->
						                
							        </div><!--//col-->
							        <div class="col-auto">
								        <h4 class="app-card-title">New Orders</h4>
							        </div><!--//col-->
						        </div><!--//row-->
						    </div><!--//app-card-header-->
						    <div class="app-card-body px-4">
							    
							    <div class="intro">
									<table class="table table-borderless mb-0">
										<thead>
											<tr>
												<th style="font-size: 15px;">Name</th>
												<th style="font-size: 15px;">Status</th>
												<th style="font-size: 15px;">MOD</th>
												<th style="font-size: 15px;">Price (₱)</th>
											</tr>
										</thead>
										<tbody>
											<tr v-for="new_order in newOrders" :key="new_order.id">
												<td style="font-size: 13px;">{{ new_order.first_name + ' ' + new_order.last_name }}</td>
												<td style="font-size: 13px;"><span style="background-color: yellow;">pending</span></td>
												<td style="font-size: 13px;">{{ new_order.mode_of_payment == 1 ? "COD" : new_order.mode_of_payment == 2 ? "Bank" : new_order.mode_of_payment == 3 ? "Pay Pal" : new_order.mode_of_payment == 4 ? "Gcash" : " " }}</td>
												<td style="font-size: 13px;">₱{{ new_order.order_total }}</td>
											</tr>
										</tbody>
									</table>
								</div>
						    </div><!--//app-card-body-->
						    <div class="app-card-footer p-4 mt-auto">
							   <router-link to="/admin/orders" class="btn app-btn-secondary" href="#">more</router-link>
						    </div><!--//app-card-footer-->
						</div><!--//app-card-->
				    </div><!--//col-->
			    </div><!--//row-->
			    
		    </div><!--//container-fluid-->
	    </div><!--//app-content-->
	    
	    
    </div><!--//app-wrapper-->    
</template>

<script>

import apiRequest from '@/services/apiService';
import showAlert from '@/services/swalAlert';
import { Line, Bar } from 'vue-chartjs';

import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement
} from 'chart.js'

ChartJS.register(
  Title, Tooltip, Legend,
  BarElement, CategoryScale, LinearScale,
  PointElement, LineElement
)

export default {
	name: 'Index',
	components: {
		Line, Bar
	},
	data() {
		return {
			total_products: null,
			total_sales : null,
			inVoices : null,
			totalCancelled : null,
			totalProductsQuantity : null,
			totalRegisteredUsers: null,
			totalOutOfStockProducts : null,
			totalActiveUsers : null,
			revenue : null,
			averageOrderValue : null,
			getMethodOfPaymentMostUsed : null,
			productLists : [],
			inVoiceList: [],
			newOrders : [],
			userGrowth : null,
			orderFulfillmentRate : null,
			newOrderPendingRate : null,
			salesGrowthMonth : null,
			salesGrowthWeek : null,
			chartData: {
				labels: [], 
				datasets: [
				{
					label: 'Total Sales',
					borderColor: '#15a362',
					backgroundColor: 'rgba(76, 175, 80, 0.2)',
					data: []
				}
				]
			},
			chartOptions: {
				responsive: true,
				maintainAspectRatio: false
			},
			animatedTotalSales: 0,
			animatedTotalCancelled: 0,
			animatedTotalProducts: 0,
			animatedInVoices: 0,
    	}
	},
	mounted() {
		this.getAdminLandingPage();
		this.fetchChartData();
	},
	methods: {
		animateCount(target, property, duration = 2000) {
			if (target === null) return; 
			let start = 0;
			const stepTime = 15; 
			const steps = duration / stepTime;
			const increment = target / steps;

			const interval = setInterval(() => {
				start += increment;
				this[property] = Math.floor(start);
				if (start >= target) {
					this[property] = target;
					clearInterval(interval);
				}
			}, stepTime);
		},
		startAnimation() {
			this.animateCount(this.total_sales, "animatedTotalSales");
			this.animateCount(this.totalCancelled, "animatedTotalCancelled");
			this.animateCount(this.total_products, "animatedTotalProducts");
			this.animateCount(this.inVoices, "animatedInVoices");
		},
		async fetchChartData() {
			try {
				const response = await apiRequest.adminLandingPage();
				const data = response.data;

				this.chartData = {
				labels: ['Total Sales', 'Invoices', 'Total Products', 'Cancelled Orders'],
				datasets: [
					{
					label: 'Sales Data',
					backgroundColor: '#15a362',
					borderColor: '#15a362',
					data: [data.totalSales, data.inVoices, data.totalProducts, data.totalCancelled]
					}
				]};
			} catch (error) {
				console.error('Error fetching chart data:', error);
			}
		},
		async getAdminLandingPage() {
			try {
				const response = await apiRequest.adminLandingPage();
				console.log(response.data);
				if (response.data.success == "true") {
					this.total_products = response.data.totalProducts;
					this.total_sales = response.data.totalSales;
					this.inVoices = response.data.inVoices;
					this.totalCancelled = response.data.totalCancelled;
					this.inVoiceList = response.data.inVoiceList;
					this.totalProductsQuantity = response.data.totalProductsQuantity;
					this.totalRegisteredUsers = response.data.totalRegisteredUsers;
					this.totalOutOfStockProducts = response.data.totalOutOfStockProducts;
					this.totalActiveUsers = response.data.totalActiveUsers;
					this.revenue = response.data.revenue;
					this.averageOrderValue = response.data.averageOrderValue;
					this.getMethodOfPaymentMostUsed = response.data.getMethodOfPaymentMostUsed;
					this.productLists = response.data.productLists;
					this.newOrders = response.data.newOrders;
					this.userGrowth = response.data.userGrowth;
					this.orderFulfillmentRate = response.data.orderFulfillmentRate;
					this.newOrderPendingRate = response.data.newOrderPendingRate;
					this.salesGrowthMonth = response.data.salesGrowthMonth;
					this.salesGrowthWeek = response.data.salesGrowthWeek;
				}
				this.startAnimation();
			} catch (error) {
				console.error('Error fetching user:', error);
			}
		}
	}
}
</script>