<?php

namespace Never5\DownloadMonitor\Ecommerce\Checkout\PaymentGateway\Test;

use Never5\DownloadMonitor\Ecommerce\Checkout\PaymentGateway;
use Never5\DownloadMonitor\Ecommerce\Services\Services;

class TestGateway extends PaymentGateway\PaymentGateway {

	/**
	 * PayPal constructor.
	 */
	public function __construct() {

		$this->set_id( 'test' );
		$this->set_title( 'Test' );
		$this->set_description( __( 'Test payments are not real payments, used for testing your website.', 'download-monitor' ) );

		parent::__construct();

	}

	/**
	 * Process the payment
	 *
	 * @param \Never5\DownloadMonitor\Ecommerce\Order\Order $order
	 *
	 * @return PaymentGateway\Result
	 */
	public function process( $order ) {

		$error_message = '';

		try {

			/** @var \Never5\DownloadMonitor\Ecommerce\Order\Repository $order_repo */
			$order_repo = Services::get()->service( 'order_repository' );


			// set status to completed
			$order->set_status( Services::get()->service( 'order_status_factory' )->make( 'completed' ) );


			// add a test transaction as well
			/** @var \Never5\DownloadMonitor\Ecommerce\Order\Transaction\OrderTransaction $dlm_transaction */
			$dlm_transaction = Services::get()->service( 'order_transaction_factory' )->make();
			$dlm_transaction->set_amount( $order->get_total() );
			$dlm_transaction->set_processor( $this->get_id() );
			$dlm_transaction->set_processor_nice_name( $this->get_title() );
			$dlm_transaction->set_processor_transaction_id( 'TEST_ID' );
			$dlm_transaction->set_processor_status( 'approved' );
			$dlm_transaction->set_status( Services::get()->service( 'order_transaction_factory' )->make_status( 'success' ) );

			// add transaction to order
			$order->add_transaction( $dlm_transaction );

			$order_repo->persist( $order );

			return new PaymentGateway\Result( true, $this->get_success_url( $order ) );

		} catch ( \Exception $exception ) {
			$error_message = $exception->getMessage();
		}

		return new PaymentGateway\Result( false, '', $error_message );

	}


}