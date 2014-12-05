<?php

	namespace MrAudioGuy\Commons;

	use Exception;


	/**
	 * Class Error
	 *	Methods for generating and throwing user defined error with stacks and oci_error definitions
	 * @package MrAudioGuy\Commons
	 */
	class Error
	{

		/**
		 *	Method for generating and throwing user defined error with stacks and oci_error definitions
		 * @param Exception $exception
		 * @param array     $traces
		 * @param array     $oci_error
		 * @param bool      $suppress
		 *
		 * @return string
		 */
		public static function getMessage (Exception $exception = null, array $traces = null, array $oci_error = null,
										   $suppress = false)
		{
			$exceptionMessage = "";
			$traceMessage     = "";
			$dbMessage        = "";

			if (isset($exception))
			{
				$exceptionMessage .= <<<"EXC"
	{"code":"{$exception->getCode()}","message":"{$exception->getMessage()}"}
EXC;

			}

			if (isset($traces) && isset($traces[0]) && !empty($traces[0]))
			{
				foreach ($traces as $key => $trace)
				{
					$args = "";
					foreach ($trace['args'] as $k => $v)
					{
						if (is_array($v))
						{
							$args .= " array,";
						}
						elseif (is_object($v) && !method_exists($v, '__toString'))
						{
							$args .= " object,";
						}
						else
						{
							$args .= " ". gettype($v) . "($v),";
						}
					}
					$args = trim($args, " ,");

					$traceMessage .= "{\"step\":\"$key\"";
					$traceMessage .= isset($trace['file']) ? ",\"file\":\"{$trace['file']}\"" : "";
					$traceMessage .= isset($trace['line']) ? ",\"file\":\"{$trace['line']}\"" : "";
					$traceMessage .= isset($trace['function']) ?
						",\"ref\":\"" .
						(isset($trace['class']) ? $trace['class'] . $trace['type'] : "") .
						"{$trace['function']}($args)\"" : "";
					$traceMessage .= "},";
				}
				$traceMessage = trim($traceMessage, ",");
			}

			if (isset($oci_error) && !empty($oci_error))
			{
				$dbMessage = <<<"STR"
	{"code":"{$oci_error['code']}","message":"{$oci_error['message']}","offset":"{$oci_error['offset']}","sqltext":"{$oci_error['sqltext']}"}
STR;
			}

			$message = <<<"MSG"
	{"exception":[$exceptionMessage],"db":[$dbMessage],"trace":[$traceMessage]}
MSG;
			if (!$suppress)
			{
				trigger_error($message, E_USER_ERROR);
			}

			return $message;
		}
	}