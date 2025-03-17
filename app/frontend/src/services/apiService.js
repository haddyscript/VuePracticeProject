import axios from 'axios';
const baseApiUrl = import.meta.env.VITE_BASEURL;

const apiRequest = axios.create({
    baseURL :baseApiUrl,
    headers: {
        // 'Content-Type': 'application/json',
        'content-type': 'multipart/form-data',
         "Access-Control-Allow-Origin": "*",
        "Access-Control-Allow-Methods": "GET,PUT,POST,DELETE,PATCH,OPTIONS",
        credentials:true,            //access-control-allow-credentials:true
        optionSuccessStatus:200
    }
});

apiRequest.interceptors.request.use((config => {
    const token = localStorage.getItem('admin_token');
    const userToken = localStorage.getItem('token');
    if(token){
        config.headers.Authorization = `Bearer ${token}`;
    }
    if(userToken){
        config.headers.Authorization = `Bearer ${userToken}`
    }
    return config;
}),
(error => {
    return Promise.reject(error);
}));

console.log("Admin" , localStorage.getItem('admin_token'));
console.log("User" , localStorage.getItem('token'));

const apiServicesToReUse = {

    //-----------------------------------//
    // This is for user api route section//
    //-----------------------------------//
    getCountry(){
        return apiRequest.get('/get_country');
    },
    userRegister(formData){
        return apiRequest.post('/register', formData);
    },
    userLogin(formData){
        return apiRequest.post('/login', formData);
    },
    getUser(){
        return apiRequest.get('/user');
    },
    updateProfilePicture(formData){
        return apiRequest.post('/user/update_profile_picture', formData);
    },
    updateUser(formData){
        return apiRequest.post('/user/update_user', formData);
    },
    userLogout(){
        return apiRequest.post('/logout');
    },
    user_getAllProducts(params){
        return apiRequest.get('/user/user_get_all_products', { params });
    },
    addToCart(formData){
        return apiRequest.post('/user/add_to_cart', formData);
    },
    getCartItems(formData){
        return apiRequest.post('/user/get_products_in_cart', formData);
    },
    removeFromCart(formData){
        return apiRequest.post('/user/delete_product_in_cart', formData);
    },
    applyCoupon(formData){
        return apiRequest.post('/user/apply_coupon', formData);
    },
    getCartCountItems(formData){
        return apiRequest.post('/user/get_cart_count', formData);
    },
    proceedToCheckout(formData){
        return apiRequest.post('/user/proceed_to_checkout', formData);
    },
    getCheckoutDetails(formData){
        return apiRequest.post('/user/checkout_items', formData);
    },
    placeOrder(formData){
        return apiRequest.post('/user/place_order', formData);
    },
    getMyOrders(formData){
        return apiRequest.post('/user/get_order', formData);
    },

    //-------------------------------------//
    // This is for admin section api routes//
    //-------------------------------------//

    adminRegister(formData){
        return apiRequest.post('/admin/register', formData);
    },
    adminLogin(formData){
        return apiRequest.post('/admin/login', formData);
    },
    adminLogout(formData){
        return apiRequest.post('/admin/logout', formData);
    },
    adminLandingPage(){
        return apiRequest.get('/admin_landing_page');
    },
    adminGetUserList(formData){
        return apiRequest.post('/admin/get_user_list', formData);
    },
    adminDeactivateUser(formData){
        return apiRequest.post('admin/deactivate_user', formData);
    },
    getAdminInfo(formData){
        return apiRequest.post('/admin', formData);
    },
    updateAdminProfilePicture(formData){
        return apiRequest.post('/admin/update_profile_picture', formData);
    },
    updateAdminOnebyOneInfo(payload){
        return apiRequest.post('/admin/update_detail', payload);
    },
    createBusinessDetails(formData){    
        return apiRequest.post('/admin/create_buiness_detail', formData);
    },
    getBusinessDetails(){ 
        return apiRequest.get('/admin/get_business_detail');
    },
    addProduct(formData){
        return apiRequest.post('/admin/add_product', formData);
    },
    getAllProducts(params){
        return apiRequest.get('/admin/get_all_products', { params });
    },
    getProductById(id){
        return apiRequest.get(`/admin/get_product/${id}`);
    },
    updateProductById(formData){
        return apiRequest.post('/admin/update_certain_product', formData);
    },
    deleteProductById(id){
        return apiRequest.delete(`/admin/delete_certain_product/${id}`);
    },
    liveOrUnliveProduct(id){
        return apiRequest.post(`/admin/live_or_unlive_product/${id}`);
    },
    addAboutUsDetails(formData){
        return apiRequest.post('/admin/create_pages', formData);
    },
    editPages(formData){
        return apiRequest.post('/admin/update_pages', formData);
    },
    getCertainPageDetailsToEdit(params){
        return apiRequest.get('/admin/get_certain_page_to_edit', {params});
    },
    getCertainPage(params){
        return apiRequest.get('/admin/get_page', {params});
    },
    deletePageDetail(params){
        return apiRequest.delete('/admin/delete_page_detail', {params});
    },
    getAllCoupon(){
        return apiRequest.get('/admin/get_all_coupon');
    },
    addCoupon(formData){
        return apiRequest.post('/admin/add_coupon', formData);
    },
    editCoupon(formData){
        return apiRequest.post('/admin/edit_coupon', formData);
    },
    deleteCoupon(formData){
        return apiRequest.post('/admin/delete_coupon', formData);
    },
    getOrderList(formData){
        return apiRequest.post('/admin/get_order_list', formData);
    },

    cancelOrder(formData){
        return apiRequest.post('/cancel_order', formData);
    }
};

export default apiServicesToReUse;