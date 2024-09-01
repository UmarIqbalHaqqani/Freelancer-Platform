<?php

namespace Hostinger\EasyOnboarding\Amplitude;


use Hostinger\WpHelper\Utils as Helper;
use Hostinger\Amplitude\AmplitudeManager;
use Hostinger\EasyOnboarding\Amplitude\Actions;

defined( 'ABSPATH' ) || exit;

class Amplitude {
	public const AMPLITUDE_ENDPOINT = '/v3/wordpress/plugin/trigger-event';
	private const WOO_WIZARD_PAGE = 'page=wc-admin&path=/setup-wizard';
	private const WOO_ONBOARDING_COMPLETED = 'woocommerce_default_onboarding_completed';
	private const AMPLITUDE_HOME_SLUG = 'home';
	private const AMPLITUDE_LEARN_SLUG = 'learn';
	private const AMPLITUDE_AI_ASSISTANT_SLUG = 'ai-assistant';
	private const AMAZON_AFFILIATE_SLUG = 'amazon_affiliate';
	private const WOO_REQUIRED_ONBOARDING_STEPS = array( 'products', 'payments' );
	private const WOO_ONBOARDING_TRANSIENT_REQUEST = 'woocommerce_onboarding_request';
	private const WOO_ONBOARDING_TRANSIENT_RESPONSE = 'woocommerce_onboarding_response';
	private const WOO_ONBOARDING_TRANSIENT_ATTEMPTS = 'woocommerce_onboarding_attempts';
	private const WOO_ONBOARDING_STARTED_TRANSIENT_ATTEMPTS = 'woocommerce_started_attempts';
	private const WOO_ONBOARDING_STARTED_TRANSIENT_REQUEST = 'woocommerce_started_request';
	private const WOO_ONBOARDING_STARTED_TRANSIENT_RESPONSE = 'woocommerce_started_response';
	private const CACHE_SIX_HOURS = 3600 * 6;
	private const CACHE_ONE_HOUR = 3600;
	public const CLIENT_WOO_COMPLETED_ACTIONS = 'woocommerce_task_list_tracked_completed_tasks';

	private Helper $helper;
	private AmplitudeManager $amplitudeManager;

	public function __construct(
		Helper $helper,
		AmplitudeManager $amplitudeManager
	) {

		$this->amplitudeManager = $amplitudeManager;
		$this->helper           = $helper;

		if ( $this->helper::IsPluginActive( 'woocommerce' ) ) {
			$this->setup_woocommerce_onboarding_events();
		}
	}

	private function setup_woocommerce_onboarding_events(): void {

		if ( defined( 'DOING_AJAX' ) && \DOING_AJAX ) {
			return;
		}

		if ( $this->helper->isThisPage( self::WOO_WIZARD_PAGE ) ) {
			add_action( 'admin_init', array( $this, 'woocommerce_onboarding_started' ) );
		}

		if ( ! $this->helper::getSetting( self::WOO_ONBOARDING_COMPLETED ) ) {
			add_action( 'admin_init', array( $this, 'woocommerce_onboarding_completed' ) );
		}
	}

	public function track_menu_action( string $event_action, string $location ): void {
		$endpoint = self::AMPLITUDE_ENDPOINT;

		if ( empty( $event_action ) ) {
			return;
		}

		$params = array(
			'action'   => $event_action,
			'location' => $location,
		);

		$this->amplitudeManager->sendRequest( $endpoint, $params );
	}

	public function setup_store( string $event_action ): void {
		$amplitude_actions = new Actions();
		$endpoint          = self::AMPLITUDE_ENDPOINT;
		$action            = sanitize_text_field( $event_action );

		if ( $amplitude_actions::WOO_STORE_SETUP_STORE !== $action ) {
			return;
		}

		$params = array(
			'action' => $action,
		);

		$this->amplitudeManager->sendRequest( $endpoint, $params );
	}

	public function woocommerce_onboarding_started(): void {
		$transient_response_key = self::WOO_ONBOARDING_STARTED_TRANSIENT_RESPONSE;
		$transient_attempts_key = self::WOO_ONBOARDING_STARTED_TRANSIENT_ATTEMPTS;
		$onboarding_started     = get_transient( $transient_response_key );
		$request_attempts       = get_transient( $transient_attempts_key );
		$amplitude_actions      = new Actions();

		if ( false === $request_attempts ) {
			$request_attempts = 0;
		}

		if ( $onboarding_started || $request_attempts > 20 ) {
			return;
		}

		try {
			set_transient( self::WOO_ONBOARDING_STARTED_TRANSIENT_REQUEST, true, self::CACHE_SIX_HOURS );

			if ( false === get_transient( self::WOO_ONBOARDING_STARTED_TRANSIENT_REQUEST ) ) {
				throw new \Exception( 'Unable to create transient in WordPress.' );
			}

			if ( $this->helper->isThisPage( self::WOO_WIZARD_PAGE ) ) {
				$request = $this->amplitudeManager->sendRequest( self::AMPLITUDE_ENDPOINT, array( 'action' => $amplitude_actions::WOO_ONBOARDING_STARTED ) );

				if ( wp_remote_retrieve_response_code( $request ) == 200 ) {
					set_transient( $transient_response_key, true, self::CACHE_SIX_HOURS );
				}
			}

			set_transient( $transient_response_key, 0, self::CACHE_ONE_HOUR );
		} catch ( \Exception $exception ) {
			$this->helper->errorLog( 'Error checking onboarding started: ' . $exception->getMessage() );
		} finally {
			set_transient( $transient_attempts_key, ++ $request_attempts, self::CACHE_SIX_HOURS );
		}
	}

	public function woocommerce_onboarding_completed(): void {
		$transient_request_key  = self::WOO_ONBOARDING_TRANSIENT_REQUEST;
		$transient_response_key = self::WOO_ONBOARDING_TRANSIENT_RESPONSE;
		$transient_attempts_key = self::WOO_ONBOARDING_TRANSIENT_ATTEMPTS;
		$onboarding_completed   = get_transient( $transient_response_key );
		$request_attempts       = get_transient( $transient_attempts_key ) ?? 0;
		$amplitude_actions      = new Actions();
		$completed_steps        = $this->default_woocommerce_survey_steps_completed( self::WOO_REQUIRED_ONBOARDING_STEPS );

		try {
			if ( $onboarding_completed || $request_attempts > 20 ) {
				return;
			}

			set_transient( $transient_request_key, true, self::CACHE_SIX_HOURS );

			if ( false === get_transient( $transient_request_key ) ) {
				throw new \Exception( 'Unable to create transient in WordPress.' );
			}

			if ( $completed_steps ) {
				$request = $this->amplitudeManager->sendRequest( self::AMPLITUDE_ENDPOINT, array( 'action' => $amplitude_actions::WOO_STORE_SETUP_COMPLETED ) );

				if ( wp_remote_retrieve_response_code( $request ) == 200 ) {
					set_transient( $transient_response_key, true, self::CACHE_SIX_HOURS );
					$this->helper->updateSetting( self::WOO_ONBOARDING_COMPLETED, true );
				}

				set_transient( $transient_response_key, 0, self::CACHE_ONE_HOUR );
			}
		} catch ( \Exception $exception ) {
			$this->helper->errorLog( 'Error checking onboarding completion: ' . $exception->getMessage() );
		} finally {
			set_transient( $transient_attempts_key, ++ $request_attempts, self::CACHE_SIX_HOURS );
		}
	}

	public function default_woocommerce_survey_steps_completed( array $steps ): bool {
		$completed_actions = get_option( self::CLIENT_WOO_COMPLETED_ACTIONS, array() );

		return empty( array_diff( $steps, $completed_actions ) );
	}
}
