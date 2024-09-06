<?php
/**
 * =======================================================================================
 *                           GemFramework (c) GemPixel
 * ---------------------------------------------------------------------------------------
 *  This software is packaged with an exclusive framework as such distribution
 *  or modification of this framework is not allowed before prior consent from
 *  GemPixel. If you find that this framework is packaged in a software not distributed
 *  by GemPixel or authorized parties, you must not use this software and contact GemPixel
 *  at https://gempixel.com/contact to inform them of this misuse.
 * =======================================================================================
 *
 * @package GemPixel\Premium-URL-Shortener
 * @author GemPixel (https://gempixel.com)
 * @license https://gempixel.com/licenses
 * @link https://gempixel.com
 */

namespace Traits;

use Core\DB;
use Core\Helper;
use Core\Request;
use Helpers\Payments\Bank;
use Helpers\Payments\Paypal;
use Helpers\Payments\Stripe;
use Helpers\Payments\PaypalApi;
use Helpers\Payments\Paddle;
use Helpers\Payments\PayStack;

trait Payments {

  /**
   * Payment List
   *
   * @author GemPixel <https://gempixel.com>
   * @version 6.0
   * @param string|null $type
   * @param string|null $action
   * @return void
   */
    public function processor($type = null, $action = null){

        $list = [
			'paypal' => [
				'provider' => 'Paypal Single Payment',
				'name' => e('PayPal'),
				'logos' => [
					'<svg width="35" viewBox="0 0 780 500" width="780" xmlns="http://www.w3.org/2000/svg"><path d="m725 0h-670c-30.327 0-55 24.673-55 55v391c0 30.327 24.673 55 55 55h670c30.325 0 55-24.673 55-55v-391c0-30.327-24.675-55-55-55z" fill="#fff"/><path d="m168.38 169.85c-8.399-5.774-19.359-8.668-32.88-8.668h-52.346c-4.145 0-6.435 2.073-6.87 6.214l-21.265 133.48c-.221 1.311.107 2.51.981 3.6.869 1.093 1.962 1.636 3.271 1.636h24.864c4.361 0 6.758-2.068 7.198-6.216l5.888-35.985c.215-1.744.982-3.162 2.291-4.254 1.308-1.09 2.944-1.804 4.907-2.13 1.963-.324 3.814-.487 5.562-.487 1.743 0 3.814.11 6.217.327 2.397.218 3.925.324 4.58.324 18.756 0 33.478-5.285 44.167-15.866 10.684-10.577 16.032-25.244 16.032-44.004 0-12.868-4.202-22.192-12.597-27.975zm-26.99 40.08c-1.094 7.635-3.926 12.649-8.506 15.049-4.581 2.403-11.124 3.597-19.629 3.597l-10.797.328 5.563-35.007c.434-2.397 1.851-3.597 4.252-3.597h6.218c8.72 0 15.049 1.257 18.975 3.761 3.924 2.51 5.233 7.802 3.924 15.869z" fill="#003087"/><path d="m720.79 161.18h-24.208c-2.405 0-3.821 1.2-4.253 3.599l-21.267 136.1-.328.654c0 1.096.437 2.127 1.311 3.109.868.979 1.963 1.471 3.271 1.471h21.595c4.138 0 6.429-2.068 6.871-6.215l21.265-133.81v-.325c-.002-3.053-1.424-4.58-4.257-4.58z" fill="#009cde"/><path d="m428.31 213.86c0-1.088-.438-2.126-1.306-3.106-.875-.981-1.857-1.474-2.945-1.474h-25.191c-2.404 0-4.366 1.096-5.89 3.271l-34.679 51.04-14.394-49.075c-1.096-3.488-3.493-5.236-7.198-5.236h-24.54c-1.093 0-2.075.492-2.942 1.474-.875.98-1.309 2.019-1.309 3.106 0 .44 2.127 6.871 6.379 19.303 4.252 12.434 8.833 25.848 13.741 40.244 4.908 14.394 7.468 22.031 7.688 22.898-17.886 24.43-26.826 37.518-26.826 39.26 0 2.838 1.417 4.254 4.253 4.254h25.191c2.399 0 4.361-1.088 5.89-3.271l83.427-120.4c.433-.433.651-1.193.651-2.289z" fill="#003087"/><path d="m662.89 209.28h-24.865c-3.056 0-4.904 3.599-5.559 10.797-5.677-8.72-16.031-13.088-31.083-13.088-15.704 0-29.065 5.89-40.077 17.668-11.016 11.779-16.521 25.631-16.521 41.551 0 12.871 3.761 23.121 11.285 30.752 7.524 7.639 17.611 11.451 30.266 11.451 6.323 0 12.757-1.311 19.3-3.926 6.544-2.617 11.665-6.105 15.379-10.469 0 .219-.222 1.198-.654 2.942-.44 1.748-.655 3.06-.655 3.926 0 3.494 1.414 5.234 4.254 5.234h22.576c4.138 0 6.541-2.068 7.193-6.216l13.415-85.389c.215-1.309-.111-2.507-.981-3.599-.876-1.087-1.964-1.634-3.273-1.634zm-42.694 64.452c-5.562 5.453-12.269 8.179-20.12 8.179-6.328 0-11.449-1.742-15.377-5.234-3.928-3.483-5.891-8.282-5.891-14.396 0-8.064 2.727-14.884 8.181-20.446 5.446-5.562 12.214-8.343 20.284-8.343 6.102 0 11.174 1.8 15.212 5.397 4.032 3.599 6.055 8.563 6.055 14.888-.001 7.851-2.783 14.505-8.344 19.955z" fill="#009cde"/><path d="m291.23 209.28h-24.864c-3.058 0-4.908 3.599-5.563 10.797-5.889-8.72-16.25-13.088-31.081-13.088-15.704 0-29.065 5.89-40.078 17.668-11.016 11.779-16.521 25.631-16.521 41.551 0 12.871 3.763 23.121 11.288 30.752 7.525 7.639 17.61 11.451 30.262 11.451 6.104 0 12.433-1.311 18.975-3.926 6.543-2.617 11.778-6.105 15.704-10.469-.875 2.616-1.309 4.907-1.309 6.868 0 3.494 1.417 5.234 4.253 5.234h22.574c4.141 0 6.543-2.068 7.198-6.216l13.413-85.389c.215-1.309-.112-2.507-.981-3.599-.873-1.087-1.962-1.634-3.27-1.634zm-42.695 64.614c-5.563 5.351-12.382 8.017-20.447 8.017-6.329 0-11.4-1.742-15.214-5.234-3.819-3.483-5.726-8.282-5.726-14.396 0-8.064 2.725-14.884 8.18-20.446 5.449-5.562 12.211-8.343 20.284-8.343 6.104 0 11.175 1.8 15.214 5.398 4.032 3.599 6.052 8.563 6.052 14.888 0 8.069-2.781 14.778-8.343 20.116z" fill="#003087"/><path d="m540.04 169.85c-8.398-5.774-19.356-8.668-32.879-8.668h-52.02c-4.364 0-6.765 2.073-7.197 6.214l-21.266 133.48c-.221 1.312.106 2.511.981 3.601.865 1.092 1.962 1.635 3.271 1.635h26.826c2.617 0 4.361-1.416 5.235-4.252l5.89-37.949c.216-1.744.98-3.162 2.29-4.254 1.309-1.09 2.943-1.803 4.908-2.13 1.962-.324 3.812-.487 5.562-.487 1.743 0 3.814.11 6.214.327 2.399.218 3.931.324 4.58.324 18.76 0 33.479-5.285 44.168-15.866 10.688-10.577 16.031-25.244 16.031-44.004.002-12.867-4.199-22.191-12.594-27.974zm-33.534 53.82c-4.799 3.271-11.997 4.906-21.592 4.906l-10.47.328 5.562-35.007c.432-2.397 1.849-3.597 4.252-3.597h5.887c4.798 0 8.614.218 11.454.653 2.831.44 5.562 1.799 8.179 4.089 2.618 2.291 3.926 5.618 3.926 9.98 0 9.16-2.402 15.375-7.198 18.648z" fill="#009cde"/></svg>'
				],
                'config' => ['single' => true, 'subscription' => false],
				'settings' => [PayPal::class, 'settings'],
				'save' => null,
				'checkout' => [PayPal::class, 'checkout'],
				'payment' => [PayPal::class, 'payment'],
				'subscription' => null,
				'webhook' => [PayPal::class, 'webhook'],
				'createplan' => null,
				'updateplan' => null,
				'syncplan' => null,
				'cancel' => null,
				'createcoupon' => null,
				'createtax' => null
			],
            'stripe' => [
				'provider' => 'Stripe',
				'name' => e('Credit Card'),
				'logos' => [
					'<svg fill="none" width="25" viewBox="0 0 70 48" width="70" xmlns="http://www.w3.org/2000/svg"><rect fill="#fff" height="47" rx="5.5" stroke="#d9d9d9" width="69" x=".5" y=".5"/><path clip-rule="evenodd" d="m21.2505 32.5165h-4.2406l-3.18-12.1318c-.1509-.558-.4714-1.0514-.9428-1.2839-1.1765-.5843-2.4729-1.0494-3.8871-1.2839v-.4671h6.8313c.9429 0 1.65.7016 1.7678 1.5165l1.65 8.751 4.2386-10.2675h4.1227zm8.717 0h-4.0049l3.2978-15.1667h4.0049zm8.4792-10.9651c.1179-.8168.825-1.2839 1.65-1.2839 1.2964-.1173 2.7085.1173 3.8871.6996l.7071-3.2655c-1.1786-.4671-2.4749-.7016-3.6514-.7016-3.8871 0-6.7156 2.1008-6.7156 5.0165 0 2.2181 2.0035 3.3827 3.4178 4.0843 1.53.6996 2.1193 1.1667 2.0014 1.8663 0 1.0494-1.1785 1.5165-2.355 1.5165-1.4142 0-2.8285-.3498-4.1228-.9342l-.7071 3.2675c1.4142.5823 2.9443.8169 4.3585.8169 4.3585.1152 7.0671-1.9836 7.0671-5.1338 0-3.9671-5.5371-4.1996-5.5371-5.9486zm19.5533 10.9651-3.18-15.1667h-3.4156c-.7072 0-1.4143.4671-1.65 1.1667l-5.8885 14h4.1228l.8229-2.2161h5.0656l.4714 2.2161zm-6.0064-11.0823 1.1765 5.716h-3.2978z" fill="#172b85" fill-rule="evenodd"/></svg>',
					'<svg fill="none" width="25" viewBox="0 0 70 48" width="70" xmlns="http://www.w3.org/2000/svg"><rect fill="#fff" height="47" rx="5.5" stroke="#d9d9d9" width="69" x=".5" y=".5"/><g clip-rule="evenodd" fill-rule="evenodd"><path d="m35.3945 34.7619c-2.3831 2.0565-5.4745 3.298-8.8524 3.298-7.5374 0-13.6476-6.1811-13.6476-13.8059 0-7.6249 6.1102-13.806 13.6476-13.806 3.3779 0 6.4693 1.2415 8.8524 3.2981 2.3832-2.0566 5.4745-3.2981 8.8525-3.2981 7.5373 0 13.6475 6.1811 13.6475 13.806 0 7.6248-6.1102 13.8059-13.6475 13.8059-3.378 0-6.4693-1.2415-8.8525-3.298z" fill="#ed0006"/><path d="m35.3945 34.7619c2.9344-2.5323 4.7951-6.3003 4.7951-10.5079 0-4.2077-1.8607-7.9757-4.7951-10.5079 2.3832-2.0566 5.4745-3.2981 8.8525-3.2981 7.5373 0 13.6475 6.1811 13.6475 13.806 0 7.6248-6.1102 13.8059-13.6475 13.8059-3.378 0-6.4693-1.2415-8.8525-3.298z" fill="#f9a000"/><path d="m35.3946 13.7461c2.9344 2.5323 4.7951 6.3002 4.7951 10.5079 0 4.2076-1.8607 7.9755-4.7951 10.5078-2.9343-2.5323-4.795-6.3002-4.795-10.5078 0-4.2077 1.8607-7.9756 4.795-10.5079z" fill="#ff5e00"/></g></svg>'
				],
                'config' => ['single' => true, 'subscription' => true],
				'settings' => [Stripe::class, 'settings'],
				'save' => null,
				'checkout' => [Stripe::class, 'checkout'],
				'payment' => [Stripe::class, 'payment'],
				'subscription' => [Stripe::class, 'subscription'],
				'webhook' => [Stripe::class, 'webhook'],
				'createplan' => [Stripe::class, 'createplan'],
				'updateplan' => [Stripe::class, 'updateplan'],
				'syncplan' => [Stripe::class, 'syncplan'],
				'cancel' => [Stripe::class, 'cancel'],
				'createcoupon' => [Stripe::class, 'createcoupon'],
				'createtax' => [Stripe::class, 'createtax'],
				'manage' => [Stripe::class, 'manage']
			],
			'paypalapi' => [
				'provider' => 'PayPal API',
				'name' => e('PayPal'),
				'logos' => [
					'<svg width="35" viewBox="0 0 780 500" width="780" xmlns="http://www.w3.org/2000/svg"><path d="m725 0h-670c-30.327 0-55 24.673-55 55v391c0 30.327 24.673 55 55 55h670c30.325 0 55-24.673 55-55v-391c0-30.327-24.675-55-55-55z" fill="#fff"/><path d="m168.38 169.85c-8.399-5.774-19.359-8.668-32.88-8.668h-52.346c-4.145 0-6.435 2.073-6.87 6.214l-21.265 133.48c-.221 1.311.107 2.51.981 3.6.869 1.093 1.962 1.636 3.271 1.636h24.864c4.361 0 6.758-2.068 7.198-6.216l5.888-35.985c.215-1.744.982-3.162 2.291-4.254 1.308-1.09 2.944-1.804 4.907-2.13 1.963-.324 3.814-.487 5.562-.487 1.743 0 3.814.11 6.217.327 2.397.218 3.925.324 4.58.324 18.756 0 33.478-5.285 44.167-15.866 10.684-10.577 16.032-25.244 16.032-44.004 0-12.868-4.202-22.192-12.597-27.975zm-26.99 40.08c-1.094 7.635-3.926 12.649-8.506 15.049-4.581 2.403-11.124 3.597-19.629 3.597l-10.797.328 5.563-35.007c.434-2.397 1.851-3.597 4.252-3.597h6.218c8.72 0 15.049 1.257 18.975 3.761 3.924 2.51 5.233 7.802 3.924 15.869z" fill="#003087"/><path d="m720.79 161.18h-24.208c-2.405 0-3.821 1.2-4.253 3.599l-21.267 136.1-.328.654c0 1.096.437 2.127 1.311 3.109.868.979 1.963 1.471 3.271 1.471h21.595c4.138 0 6.429-2.068 6.871-6.215l21.265-133.81v-.325c-.002-3.053-1.424-4.58-4.257-4.58z" fill="#009cde"/><path d="m428.31 213.86c0-1.088-.438-2.126-1.306-3.106-.875-.981-1.857-1.474-2.945-1.474h-25.191c-2.404 0-4.366 1.096-5.89 3.271l-34.679 51.04-14.394-49.075c-1.096-3.488-3.493-5.236-7.198-5.236h-24.54c-1.093 0-2.075.492-2.942 1.474-.875.98-1.309 2.019-1.309 3.106 0 .44 2.127 6.871 6.379 19.303 4.252 12.434 8.833 25.848 13.741 40.244 4.908 14.394 7.468 22.031 7.688 22.898-17.886 24.43-26.826 37.518-26.826 39.26 0 2.838 1.417 4.254 4.253 4.254h25.191c2.399 0 4.361-1.088 5.89-3.271l83.427-120.4c.433-.433.651-1.193.651-2.289z" fill="#003087"/><path d="m662.89 209.28h-24.865c-3.056 0-4.904 3.599-5.559 10.797-5.677-8.72-16.031-13.088-31.083-13.088-15.704 0-29.065 5.89-40.077 17.668-11.016 11.779-16.521 25.631-16.521 41.551 0 12.871 3.761 23.121 11.285 30.752 7.524 7.639 17.611 11.451 30.266 11.451 6.323 0 12.757-1.311 19.3-3.926 6.544-2.617 11.665-6.105 15.379-10.469 0 .219-.222 1.198-.654 2.942-.44 1.748-.655 3.06-.655 3.926 0 3.494 1.414 5.234 4.254 5.234h22.576c4.138 0 6.541-2.068 7.193-6.216l13.415-85.389c.215-1.309-.111-2.507-.981-3.599-.876-1.087-1.964-1.634-3.273-1.634zm-42.694 64.452c-5.562 5.453-12.269 8.179-20.12 8.179-6.328 0-11.449-1.742-15.377-5.234-3.928-3.483-5.891-8.282-5.891-14.396 0-8.064 2.727-14.884 8.181-20.446 5.446-5.562 12.214-8.343 20.284-8.343 6.102 0 11.174 1.8 15.212 5.397 4.032 3.599 6.055 8.563 6.055 14.888-.001 7.851-2.783 14.505-8.344 19.955z" fill="#009cde"/><path d="m291.23 209.28h-24.864c-3.058 0-4.908 3.599-5.563 10.797-5.889-8.72-16.25-13.088-31.081-13.088-15.704 0-29.065 5.89-40.078 17.668-11.016 11.779-16.521 25.631-16.521 41.551 0 12.871 3.763 23.121 11.288 30.752 7.525 7.639 17.61 11.451 30.262 11.451 6.104 0 12.433-1.311 18.975-3.926 6.543-2.617 11.778-6.105 15.704-10.469-.875 2.616-1.309 4.907-1.309 6.868 0 3.494 1.417 5.234 4.253 5.234h22.574c4.141 0 6.543-2.068 7.198-6.216l13.413-85.389c.215-1.309-.112-2.507-.981-3.599-.873-1.087-1.962-1.634-3.27-1.634zm-42.695 64.614c-5.563 5.351-12.382 8.017-20.447 8.017-6.329 0-11.4-1.742-15.214-5.234-3.819-3.483-5.726-8.282-5.726-14.396 0-8.064 2.725-14.884 8.18-20.446 5.449-5.562 12.211-8.343 20.284-8.343 6.104 0 11.175 1.8 15.214 5.398 4.032 3.599 6.052 8.563 6.052 14.888 0 8.069-2.781 14.778-8.343 20.116z" fill="#003087"/><path d="m540.04 169.85c-8.398-5.774-19.356-8.668-32.879-8.668h-52.02c-4.364 0-6.765 2.073-7.197 6.214l-21.266 133.48c-.221 1.312.106 2.511.981 3.601.865 1.092 1.962 1.635 3.271 1.635h26.826c2.617 0 4.361-1.416 5.235-4.252l5.89-37.949c.216-1.744.98-3.162 2.29-4.254 1.309-1.09 2.943-1.803 4.908-2.13 1.962-.324 3.812-.487 5.562-.487 1.743 0 3.814.11 6.214.327 2.399.218 3.931.324 4.58.324 18.76 0 33.479-5.285 44.168-15.866 10.688-10.577 16.031-25.244 16.031-44.004.002-12.867-4.199-22.191-12.594-27.974zm-33.534 53.82c-4.799 3.271-11.997 4.906-21.592 4.906l-10.47.328 5.562-35.007c.432-2.397 1.849-3.597 4.252-3.597h5.887c4.798 0 8.614.218 11.454.653 2.831.44 5.562 1.799 8.179 4.089 2.618 2.291 3.926 5.618 3.926 9.98 0 9.16-2.402 15.375-7.198 18.648z" fill="#009cde"/></svg>'
				],				
                'config' => ['single' => true, 'subscription' => true],
				'settings' => [PaypalApi::class, 'settings'],
				'save' => [PaypalApi::class, 'save'],
				'checkout' => [PaypalApi::class, 'checkout'],
				'payment' => [PaypalApi::class, 'payment'],
				'subscription' => [PaypalApi::class, 'subscription'],
				'webhook' => [PaypalApi::class, 'webhook'],
				'createplan' => [PaypalApi::class, 'createplan'],
				'updateplan' => [PaypalApi::class, 'updateplan'],
				'syncplan' => [PaypalApi::class, 'syncplan'],
				'cancel' => [PaypalApi::class, 'cancel'],
				'createcoupon' => null,
				'createtax' => null
			],
			'paddle' => [
				'provider' => 'Paddle',
				'name' => e('Credit Card'),
				'logos' => [
					'<svg fill="none" width="25" viewBox="0 0 70 48" width="70" xmlns="http://www.w3.org/2000/svg"><rect fill="#fff" height="47" rx="5.5" stroke="#d9d9d9" width="69" x=".5" y=".5"/><path clip-rule="evenodd" d="m21.2505 32.5165h-4.2406l-3.18-12.1318c-.1509-.558-.4714-1.0514-.9428-1.2839-1.1765-.5843-2.4729-1.0494-3.8871-1.2839v-.4671h6.8313c.9429 0 1.65.7016 1.7678 1.5165l1.65 8.751 4.2386-10.2675h4.1227zm8.717 0h-4.0049l3.2978-15.1667h4.0049zm8.4792-10.9651c.1179-.8168.825-1.2839 1.65-1.2839 1.2964-.1173 2.7085.1173 3.8871.6996l.7071-3.2655c-1.1786-.4671-2.4749-.7016-3.6514-.7016-3.8871 0-6.7156 2.1008-6.7156 5.0165 0 2.2181 2.0035 3.3827 3.4178 4.0843 1.53.6996 2.1193 1.1667 2.0014 1.8663 0 1.0494-1.1785 1.5165-2.355 1.5165-1.4142 0-2.8285-.3498-4.1228-.9342l-.7071 3.2675c1.4142.5823 2.9443.8169 4.3585.8169 4.3585.1152 7.0671-1.9836 7.0671-5.1338 0-3.9671-5.5371-4.1996-5.5371-5.9486zm19.5533 10.9651-3.18-15.1667h-3.4156c-.7072 0-1.4143.4671-1.65 1.1667l-5.8885 14h4.1228l.8229-2.2161h5.0656l.4714 2.2161zm-6.0064-11.0823 1.1765 5.716h-3.2978z" fill="#172b85" fill-rule="evenodd"/></svg>',
					'<svg fill="none" width="25" viewBox="0 0 70 48" width="70" xmlns="http://www.w3.org/2000/svg"><rect fill="#fff" height="47" rx="5.5" stroke="#d9d9d9" width="69" x=".5" y=".5"/><g clip-rule="evenodd" fill-rule="evenodd"><path d="m35.3945 34.7619c-2.3831 2.0565-5.4745 3.298-8.8524 3.298-7.5374 0-13.6476-6.1811-13.6476-13.8059 0-7.6249 6.1102-13.806 13.6476-13.806 3.3779 0 6.4693 1.2415 8.8524 3.2981 2.3832-2.0566 5.4745-3.2981 8.8525-3.2981 7.5373 0 13.6475 6.1811 13.6475 13.806 0 7.6248-6.1102 13.8059-13.6475 13.8059-3.378 0-6.4693-1.2415-8.8525-3.298z" fill="#ed0006"/><path d="m35.3945 34.7619c2.9344-2.5323 4.7951-6.3003 4.7951-10.5079 0-4.2077-1.8607-7.9757-4.7951-10.5079 2.3832-2.0566 5.4745-3.2981 8.8525-3.2981 7.5373 0 13.6475 6.1811 13.6475 13.806 0 7.6248-6.1102 13.8059-13.6475 13.8059-3.378 0-6.4693-1.2415-8.8525-3.298z" fill="#f9a000"/><path d="m35.3946 13.7461c2.9344 2.5323 4.7951 6.3002 4.7951 10.5079 0 4.2076-1.8607 7.9755-4.7951 10.5078-2.9343-2.5323-4.795-6.3002-4.795-10.5078 0-4.2077 1.8607-7.9756 4.795-10.5079z" fill="#ff5e00"/></g></svg>'
				],				
				'config' => ['single' => true, 'subscription' => true],
				'settings' => [Paddle::class, 'settings'],
				'save' => null,
				'checkout' => [Paddle::class, 'checkout'],
				'payment' => [Paddle::class, 'payment'],
				'subscription' => [Paddle::class, 'subscription'],
				'webhook' => [Paddle::class, 'webhook'],
				'createplan' => null,
				'updateplan' => null,
				'syncplan' => null,
				'cancel' => [Paddle::class, 'cancel'],
				'createcoupon' => null,
				'createtax' => null,
				'manage' => [Paddle::class, 'manage']
			],
			'paystack' => [
				'provider' => 'PayStack',
				'name' => e('Credit Card'),
				'logos' => [
					'<svg fill="none" width="25" viewBox="0 0 70 48" width="70" xmlns="http://www.w3.org/2000/svg"><rect fill="#fff" height="47" rx="5.5" stroke="#d9d9d9" width="69" x=".5" y=".5"/><path clip-rule="evenodd" d="m21.2505 32.5165h-4.2406l-3.18-12.1318c-.1509-.558-.4714-1.0514-.9428-1.2839-1.1765-.5843-2.4729-1.0494-3.8871-1.2839v-.4671h6.8313c.9429 0 1.65.7016 1.7678 1.5165l1.65 8.751 4.2386-10.2675h4.1227zm8.717 0h-4.0049l3.2978-15.1667h4.0049zm8.4792-10.9651c.1179-.8168.825-1.2839 1.65-1.2839 1.2964-.1173 2.7085.1173 3.8871.6996l.7071-3.2655c-1.1786-.4671-2.4749-.7016-3.6514-.7016-3.8871 0-6.7156 2.1008-6.7156 5.0165 0 2.2181 2.0035 3.3827 3.4178 4.0843 1.53.6996 2.1193 1.1667 2.0014 1.8663 0 1.0494-1.1785 1.5165-2.355 1.5165-1.4142 0-2.8285-.3498-4.1228-.9342l-.7071 3.2675c1.4142.5823 2.9443.8169 4.3585.8169 4.3585.1152 7.0671-1.9836 7.0671-5.1338 0-3.9671-5.5371-4.1996-5.5371-5.9486zm19.5533 10.9651-3.18-15.1667h-3.4156c-.7072 0-1.4143.4671-1.65 1.1667l-5.8885 14h4.1228l.8229-2.2161h5.0656l.4714 2.2161zm-6.0064-11.0823 1.1765 5.716h-3.2978z" fill="#172b85" fill-rule="evenodd"/></svg>',
					'<svg fill="none" width="25" viewBox="0 0 70 48" width="70" xmlns="http://www.w3.org/2000/svg"><rect fill="#fff" height="47" rx="5.5" stroke="#d9d9d9" width="69" x=".5" y=".5"/><g clip-rule="evenodd" fill-rule="evenodd"><path d="m35.3945 34.7619c-2.3831 2.0565-5.4745 3.298-8.8524 3.298-7.5374 0-13.6476-6.1811-13.6476-13.8059 0-7.6249 6.1102-13.806 13.6476-13.806 3.3779 0 6.4693 1.2415 8.8524 3.2981 2.3832-2.0566 5.4745-3.2981 8.8525-3.2981 7.5373 0 13.6475 6.1811 13.6475 13.806 0 7.6248-6.1102 13.8059-13.6475 13.8059-3.378 0-6.4693-1.2415-8.8525-3.298z" fill="#ed0006"/><path d="m35.3945 34.7619c2.9344-2.5323 4.7951-6.3003 4.7951-10.5079 0-4.2077-1.8607-7.9757-4.7951-10.5079 2.3832-2.0566 5.4745-3.2981 8.8525-3.2981 7.5373 0 13.6475 6.1811 13.6475 13.806 0 7.6248-6.1102 13.8059-13.6475 13.8059-3.378 0-6.4693-1.2415-8.8525-3.298z" fill="#f9a000"/><path d="m35.3946 13.7461c2.9344 2.5323 4.7951 6.3002 4.7951 10.5079 0 4.2076-1.8607 7.9755-4.7951 10.5078-2.9343-2.5323-4.795-6.3002-4.795-10.5078 0-4.2077 1.8607-7.9756 4.795-10.5079z" fill="#ff5e00"/></g></svg>'
				],				
				'config' => ['single' => true, 'subscription' => true],
				'settings' => [PayStack::class, 'settings'],
				'save' => null,
				'checkout' => [PayStack::class, 'checkout'],
				'payment' => [PayStack::class, 'payment'],
				'subscription' => [PayStack::class, 'subscription'],
				'webhook' => [PayStack::class, 'webhook'],
				'createplan' => [PayStack::class, 'createplan'],
				'updateplan' => [PayStack::class, 'updateplan'],
				'syncplan' => [PayStack::class, 'syncplan'],
				'cancel' => [PayStack::class, 'cancel'],
				'createcoupon' => null,
			],
			'bank' => [
				'provider' => e('Bank Transfer'),
				'name' => e('Bank Transfer'),
				'logos' => [
					'<i class="fa fa-bank"></i>'
				],
				'description' => e('Transfer payments via your bank'),
                'config' => ['single' => true, 'subscription' => false],
				'settings' => [Bank::class, 'settings'],
				'save' => null,
				'checkout' => [Bank::class, 'checkout'],
				'payment' => [Bank::class, 'payment'],
				'subscription' => null,
				'webhook' => null,
				'createplan' => null,
				'updateplan' => null,
				'syncplan' => null,
				'cancel' => null,
				'createcoupon' => null,
				'createtax' => null
            ]
		];

		if($extended = \Core\Plugin::dispatch('payment.extend')){
			foreach($extended as $fn){
				$list = array_merge($list, $fn);
			}
		}

		if($type && $action && isset($list[$type][$action])) return $list[$type][$action];

		if(isset($list[$type])) return $list[$type];

		return $list;
    }

}