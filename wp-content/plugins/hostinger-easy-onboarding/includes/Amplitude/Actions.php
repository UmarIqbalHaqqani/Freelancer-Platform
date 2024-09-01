<?php

namespace Hostinger\EasyOnboarding\Amplitude;

defined( 'ABSPATH' ) || exit;

class Actions {
	public const WOO_ONBOARDING_STARTED    = 'wp_admin.woocommerce_onboarding.start';
	public const WOO_STORE_SETUP_STORE     = 'wp_admin.woocommerce_onboarding.setup_store';
	public const WOO_STORE_SETUP_COMPLETED = 'wp_admin.woocommerce_onboarding.completed';
}
