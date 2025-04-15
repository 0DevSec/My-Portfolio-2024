<?php

if (!class_exists('ALCacheHelper')) :

	class ALCacheHelper {

		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		public static function getQueryParams() {
			if (!$_GET) {
				return array();
			}
			$params = $_GET;
			if ($params) {
				ksort($params);
			}
			return $params;
		}
		// phpcs:enable WordPress.Security.NonceVerification.Recommended

		public static function getRawRequestURI() {
			$request_uri = ALHelper::getRawParam('SERVER', 'REQUEST_URI');
			if (empty($request_uri)) {
				return '';
			}
			return '/' . ltrim($request_uri, '/');
		}

		public static function getRequestURIBase() {
			$request_uri = self::getRawRequestURI();
			if (empty($request_uri)) {
				return '';
			}
			$request_uri = explode('?', $request_uri);
			return reset($request_uri);
		}

		public static function getQueryString() {
			return http_build_query(self::getQueryParams());
		}

		public static function getCleanRequestURI() {
			$request_uri = self::getRequestURIBase();
			if (empty($request_uri)) {
				return '';
			}
			$query_string = self::getQueryString();
			if (empty($query_string)) {
				return $request_uri;
			}
			return $request_uri . '?' . $query_string;
		}

		public static function getCacheBasePath() {
			return WP_CONTENT_DIR . '/cache/airlift/';
		}

		public static function getRequestCachePath() {
			$host = ALHelper::getRawParam('SERVER', 'HTTP_HOST');
			$request_uri = self::getCleanRequestURI();
			return  self::getCacheBasePath() . $host . rtrim($request_uri, '/');
		}

		public static function randString($length) {
			$chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

			$str = "";
			$size = strlen($chars);
			for( $i = 0; $i < $length; $i++ ) {
				$str .= $chars[rand(0, $size - 1)]; // phpcs:ignore WordPress.WP.AlternativeFunctions.rand_rand
			}
			return $str;
		}
	}
endif;