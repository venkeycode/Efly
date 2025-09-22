<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Customer;
use App\Mail\sendRegistrationDetailstoUser;
use App\Mail\sendOrderDetailstoUser;
use App\Models\SiteSetting;
use Alert;
use Validator;
use Auth;
use Hash;
use PDF;
use Mail;
use Session;
use App\Models\ProductReturn;
use App\Models\ContactUs;
use App\Models\Reward;
use App\Models\AddressDetail;
use App\Models\State;
use App\Models\Banner;
use App\Models\Product;
use App\Models\Category;
use App\Models\Blog;
use App\Models\ProductAttribute;
use App\Models\ProductGallery;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Location;
use App\Models\Brand;
use App\Models\Coupon;
use App\Models\UnitAttribute;
use App\Models\OrderProducts;
use App\Models\OrderPayments;
use App\Models\Unit;
use App\Models\BulkOrderEnquiry;
use App\Models\Payment;
use Razorpay\Api\Api;
use App\Models\WishList;
use App\Models\Review;
use Jenssegers\Agent\Agent;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Newletter;
class PageController extends Controller
{
    protected $theme;

    public function __construct()
    {
        $this->theme='website';
    }


    public function home(){
        return view('website.home');
    }
    // public function contact(){
    //     return view('website.contact');
    // }
    // public function contact(Request $request)
    // {
    //     // Validate incoming data
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|max:255|unique:students,email',
    //         'phone' => 'required|string|max:20',
    //         'address' => 'required|string|max:255',
    //     ]);

    //     if ($validator->fails()) {
    //         // SweetAlert error if validation fails
    //         Alert::error('Error', $validator->errors()->first());
    //         return redirect()->back()->withErrors($validator)->withInput();
    //     }
    //     // Object-oriented storing
    //     $student = new Student();
    //     $student->name = $request->name;
    //     $student->email = $request->email;
    //     $student->mobile = $request->mobile;
    //     $student->address = $request->subject;
    //     $student->message = $request->message;
    //     $student->save(); // Saves to database

    //     Alert::success('Success', 'send message  successfully!');

    //     return redirect()->back();
    // }

    public function about() {
        return view('website.about');
    }
    public function destination() {
        return view('website.destination');
    }
    public function destinationdetails() {
        return view('website.destinatination-details');
    }
    public function service(){
        return view('website.service');
    }
    public function servicedetails(){
        return view('website.servicedetails');
    }
    public function activities(){
        return view('website.activities');
    }
    public function activitiesdetails(){
        return view('website.activities-details');
    }
    public function accomodation(Request $request){
        $amount= 2500;
        $people = $request->people;
        $start_date = $request->start_date;  $end_date = $request->end_date;
        $startDate = Carbon::parse($start_date);
        $endDate = Carbon::parse($end_date);
        // Get the difference in days
        $days = $startDate->diffInDays($endDate);
        return view('website.accomodation',compact('amount','people','start_date','end_date','days'));
    }
    public function confirm_booking(Request $request){
        $amount= 2500;
        $people = $request->people;
        $start_date = $request->start_date;  $end_date = $request->end_date;
        $startDate = Carbon::parse($start_date);
        $endDate = Carbon::parse($end_date);
        // Get the difference in days
        $days = $startDate->diffInDays($endDate);
        return view('website.confirm_booking',compact('amount','people','start_date','end_date','days'));
        //return view('website.confirm_booking');
    }
    public function login(){
        return view('website.login');
    }
    public function shopdetails(){
        return view('website.shop-details');
    }
    public function gallery(){
        return view('website.gallery');
    }
    public function tour(){
        return view('website.tour');
    }
    public function tourdetails(){
        return view('website.tour-details');
    }
    public function tourguide(){
        return view('website.tour-guide');
    }
    public function tourguiderdetails(){
        return view('website.tour-guider-details');
    }
    // public function activitiesdetails(){
    //     return view('website.activities-details');
    // }
    public function faq(){
        return view('website.faq');
    }
    public function price(){
        return view('website.price');
    }
    public function error(){
        return view('website.error');
    }
    public function cart(){
        return view('website.cart');
    }
    public function checkout(){
        return view('website.checkout');
    }
    public function wishlist(){
        return view('website.wishlist');
    }
    public function TermsandConditions(){
       return view('website.TermsandConditions');
        }
    public function PrivacyPolicy(){
        return view('website.PrivacyPolicy');
        }
    public function newsletter(){
        return view('website.newsletter');
    }    
    
    public function RefundPolicy(){
        return view('website.RefundPolicy');
         }
    public function blog(){
        $blogs= Blog::all();
        return view('website.blog',compact('blogs'));
    }
    public function blogDetail($slug){

        $blog= Blog::where('slug',$slug)->first();
        $previous_blog = Blog::find($blog->id -1) ?? null;
        $next_blog = Blog::find($blog->id +1) ?? null;
        $blogs= Blog::all();
        return view($this->theme.'.blog-details',compact('blog','blogs','previous_blog','next_blog'));
    }
    public function contact(){
        return view('website.contact');
    }
    public function layout(){
        $user = Auth::guard('customer')->user();
       $wishlist = WishList::where('user_id',$user->id)->get();
       $coupons = Coupon::all();
        if($user){
           $cartitems = Cart::where('user_id', $user->id)->get();
        }


        return view('layouts.web', compact('cartitems', 'user', 'wishlist', 'coupons'));
    }
    // public function register() {
    //     return view($this->theme.'.register');
    // }
    // public function userSubmit(Request $request) {
    //     $messages = [
    //         'name.alpha' => 'The first name must contain only letters.',
    //         'l_name.alpha' => 'The last name must contain only letters.',
    //         'email.regex' => 'The email must be a valid Gmail address.',
    //         'mobile.digits' => 'The mobile number must be exactly 10 digits.',
    //         'password.min' => 'The Password must be exactly 8 charecters long.',
    //         'password.regex' => 'Password must include letters, numbers and special characters',
    //     ];

    //     $rules = [
    //         'name' => 'required|alpha|max:255',
    //         'l_name' => 'required|alpha|max:255',
    //         'email' => 'required|email|regex:/^[\w\.-]+@gmail\.com$/|max:255',
    //         'mobile' => 'required|digits:10',
    //         'password' => [
    //             'required',
    //             'string',
    //             'min:8',
    //             'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
    //         ],
    //     ];

    //     $validator = Validator::make($request->all(), $rules, $messages);

    //     if ($validator->fails()) {
    //         return redirect()->back()
    //             ->withErrors($validator)
    //             ->withInput();
    //     }
    //     $customer = Customer::where('email', $request->email)->orWhere('mobile', $request->mobile)->first();
    //     if(!empty($customer)){
    //         Alert::toast('Email or Mobile are already in use', 'error');
    //         return redirect()->back()->withInput();

    //     }
    //     $user = new Customer;
    //     $user->name = $request->name;
    //     $user->l_name = $request->l_name;
    //     $user->email = $request->email;
    //     $user->mobile = $request->mobile;
    //     $user->password = bcrypt($request->password);
    //     $user->save();

    //     Mail::to($request->email)->send( new sendRegistrationDetailstoUser($request->name, $request->l_name, $request->email, $request->password));

    //     Alert::toast('Registration successfull', 'success');
    //     return redirect('login');
    // }
    // public function login() {
    //     return view($this->theme.'.customer.login');
    // }
    public function loginSubmit(Request $request) {



        $rules = [
        'mobile' => 'required',
        'password' => 'required|min:3',
        ];

        // custom error messages
        $messages = [
            'mobile.required' => 'Mobile is required.',
            // 'email.email' => 'Enter a valid email address.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be atleast 3 charecters.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);


        if ($validator->fails()) {
            Alert::toast('Please check input fields', 'warning');
            return redirect()->back();
        }

        if(Auth::guard()->attempt(['mobile' => $request->mobile, 'password' => $request->password])){
            // $request->session()->regenerate();
            return redirect(route('accomodation'));

        } else {
            Session::flash('message', 'Incorrect Credentails');

            Alert::toast('Entered Incorrect Credential', 'error');

            return redirect('login');

        }

    }
    // public function logout(Request $request){
    //     $user = Auth::guard('customer')->logout();

    //     Alert::toast("Logout Successfull", 'success');

    //     return redirect('login');

    // }
    public function dashboard (Request $request) {
        $user = Auth::guard('customer')->user();

        $orders = Order::where('user_id', $user->id)->get();

        $data['total_orders'] = $orders->count();
        $completedOrderCount = $orders->where('order_status', 'completed')->count();
        $customer = Customer::where('id', $user->id)->first();

        $pendingOrdersCount  = $orders->filter(fn($order) => in_array($order->order_status, ['pending', 'placed']))->count();
        return view($this->theme.'.customer.dashboard', compact('user', 'orders', 'data', 'pendingOrdersCount', 'completedOrderCount', 'customer'));
    }

    public function updateProfile(Request $request, $id) {
        $data = Customer::find($id);

        $data->name = $request->name;
        $data->email = $request->email;
        $data->mobile = $request->mobile;
        $data->save();
    }


    public function updatePassword(Request $request) {
        $user = Auth::guard('customer')->user();
        $customer = Customer::where('id', $user->id)->first();

        if($request->password != $request->confirm_password) {

            Alert::toast("Password and Confirm password are not same!", "error");

            return redirect()->route('customer.profile.display');

        }

        if(Hash::check($request->old_password, $customer->password)) {

           $customer->password = bcrypt($request->password);
           $customer->save();

           Alert::toast("Password Updated Successfully", "success");

        }else {
            Alert::toast("Old Password is not same", "error");
            return redirect()->route('customer.profile.display');
        }

        return redirect()->route('customer.profile.display');

    }

    public function addressDetails(){
        $address_details = AddressDetail::all();
        $states = State::all();
        $user = Auth::guard('customer')->user();
        return view($this->theme.'.address_details', compact('address_details', 'states', 'user'));
    }

    // public function removeCoupon(){
    //   if(Session::has('applied_coupon')){

    //     Session::forget('applied_coupon');
    //     Session::forget('discount');
    //     Session::save();

    //   }

    //     Alert::toast('Coupon Removed Successfully', 'success');

    //     return redirect()->route('cart');

    // }
    // public function checkout()
    // {
    //     $user = Auth::guard('customer')->user();
    //     if (!empty($user)) {
    //         $sessionId = Session::getId();
    //         $data = Cart::where('user_id', $user->id)->get();

    //         if (count($data) <= 0) {
    //             Alert::info("Please Add Items To Continue Checkout");
    //             return redirect(url('shop'));
    //         }

    //         if (!empty($user->id)) {
    //             $item = Cart::where('user_id', $user->id)->get();
    //             $products = Cart::where('user_id', $user->id)->pluck('product_id')->toArray();

    //         } else {
    //             $item = Cart::where('user_session', $sessionId)->get();
    //             $products = Cart::where('user_session', $sessionId)->pluck('product_id')->toArray();
    //         }

    //         // $states = State::where('country_id', 101)->get();
    //         $location = Location::where(['default' => 'Yes', 'user_id' => $user->id, 'delivery_type' => 'billing'])->first();
    //         $shipping = Location::where(['default' => 'Yes', 'user_id' => $user->id, 'delivery_type' => 'shipping'])->first();
    //         if (!empty($location->id) || !empty($shipping->id)) {
    //             return view($this->theme.'.checkout', compact('data', 'location', 'shipping', 'item', 'products'));
    //         } else {
    //             Alert::toast("Please Add Address To Proceed For Check Out", 'warning');
    //             return redirect(url('/checkout_billingdetails'));
    //         }


    //     } else {
    //         Alert::toast('Please Login To Continue From Chekout', 'warning');

    //         return redirect(url('/login'));

    //     }

    //     // return view($this->theme.'.checkout');
    // }
    public function checkout_billing(Request $request)
    {
        $user = Auth::guard('customer')->user();
        $cartItems = Cart::where('user_id', $user->id)->get();
        // if($request->has('coupon_code')){
        //     Session::put('applied_coupon', $request->coupon_code);
        // }
        $couponCode =  $request->coupon_code;
        if(isset($couponCode)){
            $coupon = Coupon::where('code', $couponCode)->first();
            if(!$coupon){
                Alert::toast("Invalid Coupon Code!");
                return redirect()->back();
            }
            $minPurchase = $coupon->min_purchace;

            $amount = 0;
            foreach($cartItems as $cartitem) {

             $amount += $cartitem->amount*$cartitem->quantity;

            }

            if($amount >=  $minPurchase) {
                 if($coupon->discount_type == 'amount'){
                     $discount = $coupon->discount;
                 }else if($coupon->discount_type == 'percent'){
                     $discount = ($coupon->discount/100)*$amount;

                 }
                Session::put('applied_coupon', $request->coupon_code);
                Session::put('discount', $discount);

            }else{
               Alert::toast("This coupon cannot be applied to orders below ₹3000.");
            }

         }

        if (!empty($user)) {
            $location = Location::where(['default' => 'Yes', 'user_id' => $user->id, 'delivery_type' => 'billing'])->first();
            $shipping = Location::where(['default' => 'Yes', 'user_id' => $user->id, 'delivery_type' => 'shipping'])->first();
            $billing_address = Location::where('user_id', $user->id)->where('type', 'billing')->first();
            $shipping_address = Location::where('user_id', $user->id)->where('type', 'shipping')->first();
            $states = State::where('country_id', 101)->get();

            return view($this->theme.'.checkout', compact('location', 'shipping', 'states', 'billing_address', 'shipping_address', 'cartItems'));
        } else {
            Alert::toast('Please Login', 'warning');
            return redirect(url('/login'));
        }
    }
    public function book(Request $request){



        $user = Auth::guard()->user();

        $amount= 2500;
        $people = $request->people;
        $start_date = $request->start_date;  $end_date = $request->end_date;
        $startDate = Carbon::parse($start_date);
        $endDate = Carbon::parse($end_date);
        // Get the difference in days
        $days = $startDate->diffInDays($endDate);

        $total_amount =$amount*$people*$days;

        //creating SO
        $order = new Order;
        $order->user_id = $user->id;
        $order->invoice_id = Str::random(16);
        $order->sub_total = $total_amount ;
        // $order->actual_price = $actual_price;
        $order->order_total = $total_amount;
        // $order->tax_amount = $tax_amount;
        // $order->coupon_id = $coupon->id ?? null;
        // $order->coupon_discount = $discount ?? null;
        $order->payment_method_name = $payment_method_name ?? 'razorpay';
        $order->order_date = Carbon::now();
        $order->start_date = $startDate;
        $order->end_date = $endDate;
        $order->days = $days;
        $order->people = $people;
        $order->payment_status = "unpaid";
        $order->order_type = "online";
        $order->save();


        //create Payment
        $payment = new Payment;
        $payment->title = "Booking Payment";
        $payment->order_id = $order->id;
        $payment->date = Carbon::now();
        $payment->paymentOrderId = Str::random(10);
        $payment->status = "pending";
        $payment->amount =  $order->order_total ;
        $payment->user_id = $user->id;
        $payment->save();


            $key = 'rzp_test_CpOLrwddTSURV3';
            $secret = 'bVQzPibY6fCRLCpKRshezX1o';
            $api = new Api($key, $secret);

            // Razorpay Response
            $razorpayResponse = $api->order->create(array('receipt' => $order->invoice_id, 'amount' => $payment->amount * 100, 'currency' => 'INR', 'notes' => array('key1' => 'value3', 'key2' => 'value2')));
            $order = Order::find($order->id);
            $order->payment_method_name ='online';
            $order->save();

            $payment = Payment::find($payment->id);
            $payment->payment_method = 'online';
            $payment->paymentOrderId = $razorpayResponse['id'];
            $payment->save();




        Alert::toast('Order Created Successfully, Please Make the Payment', 'success');
        return redirect()->route('pay', $payment->id);

     //  return redirect()->route('ccavenue.payment', $payment->id);
    }

    private function generateOrderPdf($order)
    {
        $data = Order::where('id', $order->id)->first();
        $number = $this->numberToWords($data->order_total);
        $siteSettings = SiteSetting::find(1);
        $products = OrderProducts::where('order_id', $data->id)->get();

        $pdf = PDF::loadView('admin.sales.invoice', [
            'data' => $data,
            'vasu' => $data->invoice_id,
            'number' => $number,
            'products' => $products,
            'siteSettings' => $siteSettings
        ]);

        return $pdf->output();
    }

    public function checkout_payment(){
        $user = Auth::guard('customer')->user();
        $check = Location::where('user_id', $user->id)->where('type', $request->type)->first();
// test
    }
    public function  addressSubmit(Request $request){
        $user = Auth::guard('customer')->user();
        $userAddress = new AddressDetail;
        $userAddress->user_id = $user->id;
        $userAddress->address_1 = $request->address_1;
        $userAddress->address_2 = $request->address_2;
        $userAddress->city = $request->city;
        $userAddress->state = $request->state;
        $userAddress->pincode = $request->pincode;
        $userAddress->save();

        Alert::toast('Address Details Saved Successfully', 'success');
        return redirect()->back();
    }
    public function contactUs(){
        return view($this->theme.'.contact');
    }
    public function contactSubmit(Request $request){


        $messages = [
            'email.required' => 'Email is required.',
            'email.email' => 'Enter a valid email address!',
            'email.unique' => 'This email is already registered!',
            'email.regex' => 'Only Gmail addresses are allowed.',

            'mobile.required' => 'Mobile number is required.',
            'mobile.digits' => 'Mobile number must be exactly 10 digits.',
            'mobile.regex' => 'Enter a valid mobile number starting with 6, 7, 8, or 9.',

            ];

        $rules = [
            'email' => 'required|email|regex:/^[\w\.-]+@gmail\.com$/|max:255',
            'mobile' => 'required|digits:10|regex:/^[6-9]\d{9}$/',

        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = new contactUs;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->subject = $request->subject;
        $user->message = $request->message;
        $user->save();

        Alert::toast('Thank you! Your message has been sent successfully.', 'success');
        return redirect()->back();
    }
    public function register(){
        return view($this->theme.'.register');
    }
    public function registerSubmit(Request $request){


        // $messages = [
        //     'email.required' => 'Email is required.',
        //     'email.email' => 'Enter a valid email address!',
        //     'email.unique' => 'This email is already registered!',
        //     'email.regex' => 'Only Gmail addresses are allowed.',

        //     'mobile.required' => 'Mobile number is required.',
        //     'mobile.digits' => 'Mobile number must be exactly 10 digits.',
        //     'mobile.regex' => 'Enter a valid mobile number starting with 6, 7, 8, or 9.',

        //     ];

        // $rules = [
        //     'email' => 'required|email|regex:/^[\w\.-]+@gmail\.com$/|max:255',
        //     'mobile' => 'required|digits:10|regex:/^[6-9]\d{9}$/',

        // ];

        // $validator = Validator::make($request->all(), $rules, $messages);

        // if($validator->fails()){
        //     return redirect()->back()->withErrors($validator)->withInput();
        // }

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->password = bcrypt($request->password);
        // $user->confirmpassword = $request->confirmpassword;
        $user->save();

        Alert::toast('Thank you! Your register has been sent successfully.', 'success');
        return redirect(route('login'));
    }



    public function products(Request $request){

        $user = Auth::guard('customer')->user();
        // $products = Product::query();
        // $products = $products->where('product_status', 'Show');


        // if ($request->filter == 'asc') {
        //     $products = $products->orderBy('product_name', 'ASC');
        // } else if ($request->filter == 'desc') {
        //     $products = $products->orderBy('product_name', 'DESC');
        // } else if ($request->filter == 'popularity') {
        //     $products = $products->orderBy('orders', 'DESC');
        // } else if ($request->filter == 'latest') {
        //     $products = $products->orderBy('created_at', 'DESC');
        // }
        // if (!empty($request->brands)) {
        //     $products = $products->whereIn('brand_id', $request->brands);
        // }
        //  if (!empty($request->category)) {
        //     $products = $products->where('category_id', $request->category);
        // }
        // // if (!empty($request->category)) {
        // //     $products = $products->whereIn('category_id', $request->category);
        // // }
        // if (!empty($request->k)) {
        //     $products = $products->where('product_name', 'like', '%' . $request->k . '%');
        // }
        // $products = $products->paginate(24);
        $minPrice = ProductAttribute::min('sale_price');
        $maxPrice = ProductAttribute::max('sale_price');
        $products = Product::where('product_status', 'Show')
        ->when($request->filter, function ($query) use ($request) {
            if ($request->filter == 'asc') {
                $query->orderBy('product_name', 'ASC');
            } elseif ($request->filter == 'desc') {
                $query->orderBy('product_name', 'DESC');
            } elseif ($request->filter == 'popularity') {
                $query->orderBy('orders', 'DESC');
            } elseif ($request->filter == 'latest') {
                $query->orderBy('created_at', 'DESC');
            }
        })
        ->when(!empty($request->brands), function ($query) use ($request) {
            $query->whereIn('brand_id', $request->brands);
        })
        ->when(!empty($request->category), function ($query) use ($request) {
            $query->where('category_id', $request->category);
        })
        ->when(!empty($request->k), function ($query) use ($request) {
            $query->where('product_name', 'like', '%' . $request->k . '%');
        })
        ->whereHas('attributes', function ($query) use ($request) {
            if (!empty($request->min_price) && !empty($request->max_price)) {
                $query->whereBetween('sale_price', [$request->min_price, $request->max_price]);
            }
        })
        ->paginate(24);
        $categories = Category::where(['parent_id' => 0, 'status' => 'show'])->get();
        $brands = Brand::where('status', 'show')->get();
        $totalProd = Product::where('product_status', 'Show')->count();


        if ($request->ajax()) {
            return view($this->theme.'.shop.products', compact('products'))->render();
        }

        return view($this->theme.'.shop', compact('products', 'categories', 'brands', 'totalProd','minPrice','maxPrice'))->render();
    }

    public function product(Request $request, $slug)
    {
        $user = Auth::guard('customer')->user();
        $data = Product::where(['products_slug' => $slug, 'product_status' => 'Show'])->first();
        $all_products = Product::take(4)->get();
        $product_get = Product::all();
        $productAttributes = ProductAttribute::where(['product_id' => $data->id, 'status' => 'show'])->get();

        $in_whislist = 'no';

       if(isset($user->id)){

            $check = WishList::where(['product_id' => $data->id, 'user_id' => $user->id])->first();

            if($check){
                $in_whislist = 'yes';
            }
       }


        $product_previous = Product::find($data->id-1) ?? null;
        $product_next = Product::find($data->id +1) ?? null;

        $coupons = Coupon::get();
        if (isset($data->id)) {
            $data->views +=1;
            $data->save();
            $galleries = ProductGallery::where('product_id', $data->id)->get();
            $products = Product::where(['category_id' => $data->category_id, 'product_status' => 'Show'])->inRandomOrder()->take(6)->get();
            if (isset($request->k)) {
                $rate = ProductAttribute::where(['id' => $request->k, 'product_id' => $data->id, 'status' => 'show'])->first();
                if (empty($rate->id)) {
                    return redirect(url('/404'));
                }
            } else {
                $rate = ProductAttribute::where(['product_id' => $data->id, 'status' => 'show'])->first();
                return redirect(route('product', "$slug?k=$rate->id"));
            }

            $reviews = Review::where('product_id', $data->id)->get();

            // Count total reviews
            $totalReviews = $reviews->count();

            // Count each rating (5-star, 4-star, etc.)
            $ratings = [
                5 => $reviews->where('rating', 5)->count(),
                4 => $reviews->where('rating', 4)->count(),
                3 => $reviews->where('rating', 3)->count(),
                2 => $reviews->where('rating', 2)->count(),
                1 => $reviews->where('rating', 1)->count(),
            ];

            // Calculate percentage for each rating
            $ratingPercentages = [];
            foreach ($ratings as $star => $count) {
                $ratingPercentages[$star] = $totalReviews > 0 ? round(($count / $totalReviews) * 100) : 0;
            }
         //   dd($ratingPercentages);
            // Calculate average rating
            $averageRating = $totalReviews > 0 ? round($reviews->avg('rating'), 1) : 0;

            return view($this->theme.'.shop.product', compact('data', 'rate', 'products', 'galleries','coupons','all_products', 'in_whislist', 'product_previous', 'product_next', 'product_get','ratingPercentages', 'averageRating', 'totalReviews', 'productAttributes'));
        } else {
            return redirect(url('/404'));
        }
    }


    public function certifications(){

        return view($this->theme.'.certifications');
    }

    public function reviews(){
         $reviews = Review::all();
        return view($this->theme.'.reviews', compact('reviews'));
    }

    public function ordersHistory(){
        $user = Auth::guard('customer')->user();

        $orders = Order::where('user_id', $user->id)->get();

        return view($this->theme.'.customer.dashboard-history', compact('orders', 'user'));
    }

    public function rewardsHistory(){
        $user = Auth::guard('customer')->user();

        $rewards = Reward::where('user_id', $user->id)->get();
        $PendingRewards = Reward::where('status', 'pending')->where('user_id', $user->id)->sum('rewards_total');
        $CompletedRewards = Reward::where('status', 'completed')->where('user_id', $user->id)->sum('rewards_total');
        $TotaldRewards = Reward::where('user_id', $user->id)->sum('rewards_total');

        return view($this->theme.'.customer.dashboard-rewards', compact('rewards', 'user', 'PendingRewards', 'CompletedRewards', 'TotaldRewards'));
    }

    public function orderDetails(Request $request, $id) {

        $order_products = OrderProducts::with(['attribute' => function($query) {
            $query->select('id', 'product_slug');
        }])
        ->where('order_id', $id)
        ->get();

        $currentOrder = Order::find($id);

        if(!$currentOrder){
            return response()->json([
                'success' => false,
                'message' => 'Order not found.',

            ]);

        }
        if ($order_products->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No products found for this order.',
            ]);
        }

        return response()->json([
            'success' => true,
            'order_products' => $order_products,
            'currentOrder' => $currentOrder,
        ]);
    }
    public function ordersAddress() {
        $user = Auth::guard('customer')->user();
        $billing_address = Location::where('user_id', $user->id)->where('type', 'billing')->first();
        $shipping_address = Location::where('user_id', $user->id)->where('type', 'shipping')->first();
        return view($this->theme.'.customer.dashboard-address', compact('billing_address', 'shipping_address', 'user'));
    }

    public function account_wallet(){
        $user = Auth::guard('customer')->user();
        $customer = Customer::where('id', $user->id)->first();
        return view($this->theme.'.dashboard-wallet', compact('customer', 'user'));

    }
    public function ordersSettings() {
        $user = Auth::guard('customer')->user();
        return view($this->theme.'.customer.dashboard-settings', compact('user'));
    }
    public function bulkOrders() {
        return view($this->theme.'.bulkorders');
    }
    public function faqs() {
        return view($this->theme.'.faqs');
    }
    public function addressStore(Request $request) {

        $messages = [
            'email.regex' => 'The email must be a valid Gmail address.',
            'phone.digits' => 'The mobile number must be exactly 10 digits.',
        ];

        $rules = [
            'email' => 'required|email|regex:/^[\w\.-]+@gmail\.com$/|max:255',
            'phone' => 'required|digits:10',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::guard('customer')->user();
        $check = Location::where('user_id', $user->id)->where('type', $request->type)->first();
        if($check) {
            $useraddress = Location::find($check->id);
        }else {
            $useraddress = new Location;
        }
        $useraddress->user_id = $user->id;
        $useraddress->f_name = $request->firstname;
        $useraddress->l_name = $request->lastname;
        $useraddress->c_name = $request->company_name;
        $useraddress->country = $request->country;
        $useraddress->address = $request->street;
        $useraddress->city = $request->city;
        $useraddress->landmark = $request->landmark;
        $useraddress->state = $request->state;
        $useraddress->type = $request->type;
        $useraddress->pincode = $request->zip;
        $useraddress->mobile = $request->phone;
        $useraddress->email = $request->email;
        $useraddress->save();

        Alert::toast('Address Updated Successfully', 'success');

        return redirect(url('dashboard/address'));
    }

    public function cartStore(Request $request) {
        $user = Auth::guard('customer')->user();
        $product_attribute = ProductAttribute::where('id', $request->product_attrid)->first();

        $checkCart = Cart::where(['user_id' => $user->id, 'product_attribute_id' => $product_attribute->id])->first();
        if(isset($checkCart)){

        $newcart = Cart::find($checkCart->id);
        $newcart->quantity += $request->quantity;
        $newcart->save();

        Alert::toast('Added to Cart Successfully', 'success');
        if($request->type=='buynow'){

            return redirect(route('cart'));

        }else{

            return redirect()->back();

        }

        } else {

        $unit_attribute = UnitAttribute::find($product_attribute->unit_attribute_id);
        // dd($units);
        $newcart = new Cart;
        $newcart->user_id = $user->id;
        $newcart->product_attribute_id = $request->product_attrid;
        $newcart->product_id = $product_attribute->product_id;
        $newcart->unit_attribute = $unit_attribute->attribute;
        $newcart->actual_price = $product_attribute->actual_price;
        $newcart->amount = $product_attribute->sale_price;
        $newcart->quantity = $request->quantity  ?? 1;
        $newcart->save();

        Alert::toast('Added to Cart Successfully', 'success');
        if($request->type=='buynow'){

            return redirect(route('cart'));

            }else{

                return redirect()->back();

            }
        }
    }
    public function update(Request $request, string $id){
        $cartItem = Cart::find($id);
        if(!$cartItem){
            return response->json(['success' => false, 'message' => 'Item not found']);

        }

        //Updating database with new quantity
        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        $subtotal = $cartItem->quantity * $cartItem->amount;

        return response()->json([
            'success' => true,
            'newQuantity' => $cartItem->quantity,
            'newSubtotal' => number_format($subtotal, 2)
        ]);
    }

    public function storeBulkOrders(Request $request){


        $messages = [
            'email.required' => 'Email is required.',
            'email.email' => 'Enter a valid email address!',
            'name.alpha' => 'The name must contain only letters.',
            'mobile.digits' => 'The mobile number must be exactly 10 digits.',
        ];

        $rules = [
            'name' => 'required|alpha|max:255',
            'email' => 'required|email|regex:/^[\w\.-]+@gmail\.com$/|max:255',
            'mobile' => 'required|digits:10',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();

        }

        $user = new BulkOrderEnquiry;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->company = $request->company;
        $user->requirement = $request->message;
        $user->save();

        Alert::toast('Enquiry Submitted Successfully', 'success');
        return redirect()->back();
    }

    public function pay($id){
        $payment = Payment::find($id);
        $customer = User::find($payment->user_id);
        $order = Order::where(['user_id' => $customer->id, 'id' =>  $payment->order_id])->first();
        $order_products = OrderProducts::where(['user_id' =>  $customer->id, 'order_id' => $order->id])->get();
        return view($this->theme.'.payNow', compact('payment', 'customer', 'order', 'order_products'));
    }

    // updating payment ans order status paid and completed.
    public function response(Request $request){

        $razor_key = 'rzp_test_CpOLrwddTSURV3';
        $razor_secret = 'bVQzPibY6fCRLCpKRshezX1o';
        $payment = Payment::latest()->first();
        // $api = new \Razorpay\Api\Api($razor_key, $razor_secret);
        // $razorPayment = $api->payment->fetch($request->razorpay_payment_id);


        // $user = Auth::guard()->user();

        // if($razorPayment->status == 'authorized' || $razorPayment->status == 'captured'){

        //     $payment = Payment::where('paymentOrderId', $razorPayment['order_id'])->first();
        //     $payment->status = 'paid';
        //     $payment->save();

        //     $order = Order::where('id', $payment->order_id)->first();
        //     $order->order_status = 'completed';
        //     $order->save();

        // }

        return redirect(route('dashboard.thankyou', $payment->id));

     //   return redirect(route('dashboard.history'));
    }

    //Products added to wishlist
    public function storewishlist(Request $request){

        $user = Auth::guard('customer')->user();
        $wishlistItem = WishList::where(['product_id' => $request->product_id, 'user_id' => $user->id])->first();

        if($wishlistItem){
            $wishlistItem->delete();
            Alert::toast("Removed from wishlist", "success");
            return redirect()->back();
        }

        $customer = new WishList;
        $customer->user_id = $user->id;
        $customer->product_id = $request->product_id;
        $customer->product_attribute_id = $request->product_attr;
        $customer->save();

        Alert::toast("Added to wishlist", "success");
        return redirect()->back();
    }

    //Display all wishlist products

    //Delete wishlist item
    public function removeWishlist(Request $request){


        $item = WishList::where('id', $request->wishlist_id)->delete();

        Alert::toast('Item successfully removed from your wishlist', 'success');

        return redirect()->back();

    }

    //Move wishlist item to cart
    public function Movetocart(Request $request){

        $wishlist = WishList::where('id', $request->wishlist_id)->first();
        $product = Product::find($wishlist->product_id);
        $product_attr = ProductAttribute::find($wishlist->product_attribute_id);
        $unit_attr = UnitAttribute::find($product_attr->unit_attribute_id);

        //Move wishlist item to cart
        $newcart = new Cart;
        $newcart->user_id = $wishlist->user_id;
        $newcart->product_attribute_id = $wishlist->product_attribute_id;
        $newcart->product_id = $wishlist->product_id;
        $newcart->actual_price = $product_attr->actual_price;
        $newcart->quantity = 1;
        $newcart->amount = $product_attr->sale_price;
        $newcart->unit_attribute = $unit_attr->attribute;
        $newcart->save();

        Alert::toast('Item moved to cart!', 'success');

        //Remove wishlist item after moving to cart
        $wishlist = WishList::where('id', $request->wishlist_id)->delete();
        return redirect()->back();

    }

    public function update_account_details(Request $request, $id){
        $customer = Customer::find($id);

        $messages = [

            'name.alpha' =>'The first name must contain only letters.',
            'email.regex' => 'The email must be a valid Gmail address.',
            'mobile.digits' => 'The mobile number must be exactly 10 digits.',

        ];

        $rules = [
            'name' => 'required|alpha|max:255',
            'email' => 'required|email|regex:/^[\w\.-]+@gmail\.com$/|max:255',
            'mobile' => 'required|digits:10',

        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();

        }
        if($customer) {
            $customer->name = $request->name;
            $customer->email = $request->email;
            $customer->mobile = $request->mobile;
            $customer->address = $request->address;
            if($request->hasFile('profile_pic')){

                $customer->profile_pic = $request->profile_pic->store('media');
            }

            $customer->save();

            Alert::toast('Details Updated successfully', 'success');

            return redirect()->back();

        }

    }

    public function update_password_details(Request $request, $id){
        $customer = Customer::find($id);

        if($customer){
            if(!empty( $request->password ) || !empty( $request->confirm_password ) || !empty( $request->old_password )){

                if (Hash::check($request->old_password, $customer->password)) {


                    $customer->password = bcrypt($request->password);

                    Alert::toast('Password updated successfully','success');

                }else{
                    Alert::toast('Old Password is not same!','error');
                    return redirect()->route('dashboard.settings');
                }

                if($request->password != $request->confirm_password){
                    Alert::toast('Password and Confirm password are not same!','error');

                    return redirect()->route('dashboard.settings');
                }

            }

            $customer->save();

            Alert::toast('Details Updated successfully', 'success');

            return redirect()->back();

        }

    }

    public function terms() {
        return view($this->theme.'.static.terms');
    }

    public function privacy() {
        return view($this->theme.'.static.privacy');
    }

    public function return() {
        return view($this->theme.'.static.return');
    }

    public function shipping() {
        return view($this->theme.'.static.shipping');
    }

    public function CancelOrder(Request $request, $id)
    {
        $order = Order::find($id);
        if (!$order) {
            return redirect()->back()->with('error', 'Order not found.');
        }

        $order->order_status = 'cancelled';

        $order->save();

        $customer = Customer::where('id', $order->user_id)->first();

        $order_products = OrderProducts::where('order_id', $order->id)->get();
        Mail::to($customer->email)->send( new sendOrderDetailstoUser($customer->name, $customer->l_name, $order->invoice_id, $order->order_total, $order_products, $order->delivery_address, $order->order_status));

        Alert::toast('Order status updated to Cancelled.','success');

        return redirect()->back();

    }



    public function category() {
        $categories = Category::all();
        return view($this->theme.'.category', compact('categories'));
    }

    public function forgotPassword() {
        return view($this->theme.'.forgot-password');
    }

    public function otp() {
        return view($this->theme.'.otp');
    }

    public function support() {
        return view($this->theme.'.customer.support');
    }

    public function storeTicket(Request $request){

        $user = Auth::guard('customer')->user();
        $ticket = new Ticket;
        $ticket->customer_id = $user->id;
        $ticket->title = $request->title;
        $ticket->description = $request->description;
        $ticket->save();

        Alert::toast("Your support ticket has been raised successfully! Our team will get back to you soon.", "success");
        return redirect(route('view.ticket'));

    }

    public function viewTicket() {
        $user = Auth::guard('customer')->user();
        $tickets = Ticket::where('customer_id', $user->id)->get();
        return view($this->theme.'.customer.support-ticket', compact('user', 'tickets'));
    }

    public function ReturnOrder(Request $request, $id){
        $OrderProducts = OrderProducts::find($id);

        if ($OrderProducts) {
            $return = new ProductReturn();
            $return->customer_id = $OrderProducts->user_id;
            $return->order_id = $OrderProducts->order_id;
            $return->order_product_id = $OrderProducts->id;
            $return->status = 'pending';
        //    $return->comment = $user->id;

        }

        $return->save();
        $OrderProducts->status = 'return_requested';
        $OrderProducts->save();


        Alert::toast('Product Return Added Successfully.','success');

        return redirect()->back();

    }

    public function updateRewardStatus(Request $request)
    {
        $rewardChecked = $request->input('rewardChecked');
        session()->flash('rewardChecked', $rewardChecked);

        return response()->json(['status' => 'Session updated successfully']);
    }

    public function ReviewCreate($id)
    {
        $orderProduct = Product::findOrFail($id);
        return view($this->theme.'.review.create', compact('id', 'orderProduct'));
    }

    public function ReviewStore(Request $request, $id)

    {

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'description' => 'required|string|max:1000',
        ], [
            'rating.required' => 'Please select a rating before submitting.',
            'rating.integer' => 'Invalid rating value.',
            'rating.min' => 'Rating must be at least 1.',
            'rating.max' => 'Rating cannot be more than 5.',
            'description.required' => 'Please enter a review description.',
        ]);
        $user = Auth::guard('customer')->user();

        $review = new Review();
        $review->product_id = $id;
        $review->rating = $request->rating;
        $review->review = $request->description;
        $review->customer_id = $user->id;

        $review->save();

        Alert::toast('Review Submitted Successfully.','success');

        return redirect(route('dashboard.history'));
    }

    public function storeNewLetter(Request $request) {

            $newletter = new Newletter();
            $newletter->email = $request->email;
            $newletter->save();

            Alert::toast('Thank you for subscribing!', 'success');

            return redirect()->back();

    }

    public function numberToWords($number)
    {
        $number1 = $number;
        $no = floor($number);
        $hundred = null;
        $digits_1 = strlen($no); //to find lenght of the number
        $i = 0;
        // Numbers can stored in array format
        $str = array();

        $words = array(
            '0' => '',
            '1' => 'One',
            '2' => 'Two',
            '3' => 'Three',
            '4' => 'Four',
            '5' => 'Five',
            '6' => 'Six',
            '7' => 'Seven',
            '8' => 'Eight',
            '9' => 'Nine',
            '10' => 'Ten',
            '11' => 'Eleven',
            '12' => 'Twelve',
            '13' => 'Thirteen',
            '14' => 'Fourteen',
            '15' => 'Fifteen',
            '16' => 'Sixteen',
            '17' => 'Seventeen',
            '18' => 'Eighteen',
            '19' => 'Nineteen',
            '20' => 'Twenty',
            '30' => 'Thirty',
            '40' => 'Forty',
            '50' => 'Fifty',
            '60' => 'Sixty',
            '70' => 'Seventy',
            '80' => 'Eighty',
            '90' => 'Ninety'
        );

        $digits = array('', 'Hundred', 'Thousand', 'lakh', 'Crore');
        //Extract last digit of number and print corresponding number in words till num becomes 0
        while ($i < $digits_1) {
            $divider = ($i == 2) ? 10 : 100;
            //Round numbers down to the nearest integer
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;

            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str[] = ($number < 21) ? $words[$number] . " " .
                    $digits[$counter] .
                    $plural . " " .
                    $hundred : $words[floor($number / 10) * 10] . " " .
                    $words[$number % 10] . " " .
                    $digits[$counter] . $plural . " " .
                    $hundred;
            } else
                $str[] = null;
        }

        $str = array_reverse($str);
        $result = implode('', $str);

        return $result;
    }

    public function thankyou_page($id){
        $payment = Payment::find($id);
        $customer = User::find($payment->user_id);
        $order = Order::where(['user_id' => $customer->id, 'id' =>  $payment->order_id])->first();
        $order_products = OrderProducts::where(['user_id' =>  $customer->id, 'order_id' => $order->id])->get();
        $mode = 'online';
        return view($this->theme.'.thankyou', compact('payment', 'customer', 'order', 'order_products', 'mode'));
    }


}
