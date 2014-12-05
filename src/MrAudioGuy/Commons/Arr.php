<?php

	namespace MrAudioGuy\Commons;

	/**
	 * Class Arr
	 *	An object oriented methodology for arrays
	 * @package MrAudioGuy\Commons
	 */
	class Arr
	{
		/**
		 *    Plucks keys from an array
		 *
		 * @param array $array
		 * @param       $keys
		 *
		 * @return array
		 */
		public static function pluck (array $array, $keys)
		{
			if (!is_array($keys))
			{
				$keys = func_get_args();
				array_shift($keys);
			}

			return array_intersect_key($array, array_flip($keys));
		}

		/**
		 *    Converts arrays to stdObjects, recursively
		 *
		 * @param $input
		 *
		 * @return object
		 */
		public static function toObject ($input)
		{
			if (is_array($input))
			{
				return (object)array_map(['self', __FUNCTION__], $input);
			}
			else
			{
				return $input;
			}
		}

		/**
		 *    Determines if an array is associative
		 *
		 * @param array $array
		 *
		 * @return bool
		 */
		public static function is_associative (array $array)
		{
			foreach ($array as $k => $v)
			{
				$t = str_replace((int)$k, '', $k);
				if (!empty($t))
				{
					if (!static::is_int($k))
					{
						return true;
					}
				}
			}

			return false;
		}

		/**
		 *    Determines if an array is sequential
		 *
		 * @param array $array
		 *
		 * @return bool
		 */
		public static function is_sequential (array $array)
		{
			foreach ($array as $k => $v)
			{
				if (!static::is_int($k))
				{
					return false;
				}
			}

			return true;
		}

		public static function is_int ($input)
		{
			if (is_array($input) || is_object($input))
			{
				return false;
			}
			$t = str_replace((int)$input, '', $input);
			if (!empty($t))
			{
				return false;
			}

			return true;
		}

		/**
		 *    Performs naive needle/haystack search recursively
		 *
		 * @param        $needles
		 * @param array  $haystack
		 * @param string $key
		 * @param array  $stack
		 * @param bool   $strict
		 *
		 * @return array
		 */
		public static function search ($needles, array $haystack, & $key = "", array & $stack = [], $strict = false)
		{
			if (!is_array($needles))
			{
				$needles = [$needles];
			}
			if ($strict)
			{
				foreach ($needles as $needle)
				{
					if ($needle === $haystack)
					{
						$stack[] = $key;

						return $stack;
					}
					foreach ($haystack as $k => $hay)
					{
						if ($needle === $hay)
						{
							$key .= "." . $k;
							$stack[] = $key;
						}
						elseif (is_array($hay))
						{
							$tmpKey0  = $key . "." . $k;
							$tmpStack = static::search($needle, $hay, $tmpKey0, $stack, $strict);
							if (count($tmpStack) >= count($stack))
							{
								$stack = $tmpStack;
							}
						}
					}
				}
			}
			else
			{
				foreach ($needles as $needle)
				{
					if ($needle == $haystack)
					{
						$stack[] = $key;

						return $stack;
					}
					foreach ($haystack as $k => $hay)
					{
						if ($needle == $hay)
						{
							$key .= "." . $k;
							$stack[] = $key;
						}
						elseif (is_array($hay))
						{
							$tmpKey0  = $key . "." . $k;
							$tmpStack = static::search($needle, $hay, $tmpKey0, $stack);
							if (count($tmpStack) >= count($stack))
							{
								$stack = $tmpStack;
							}
						}
					}
				}
			}

			return $stack;
		}
	}