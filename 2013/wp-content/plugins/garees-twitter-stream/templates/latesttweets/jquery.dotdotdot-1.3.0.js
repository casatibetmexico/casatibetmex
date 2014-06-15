/*	
 *	jQuery dotdotdot 1.3.0
 *	
 *	Copyright (c) 2011 Fred Heusschen
 *	www.frebsite.nl
 *
 *	Plugin website:
 *	dotdotdot.frebsite.nl
 *
 *	Dual licensed under the MIT and GPL licenses.
 *	http://en.wikipedia.org/wiki/MIT_License
 *	http://en.wikipedia.org/wiki/GNU_General_Public_License
 */

(function($) {
	if ($.fn.dotdotdot) return;

	$.fn.dotdotdot = function(o) {
		if (this.length == 0) {
			debug(true, 'No element found for "'+this.selector+'".');
			return this;
		}
		if (this.length > 1) {
			return this.each(function() {
				$(this).dotdotdot(o);
			});
		}


		var $dot = this,
			$tt0 = this[0];

		if ($dot.data('dotdotdot')) {
			$dot.trigger('destroy');
		}

		$dot.bind_events = function() {
			$dot.bind('update.dot', function(e, stop) {
				e.stopPropagation();

				opts.maxHeight = (typeof opts.height == 'number') ? opts.height : getTrueInnerHeight($dot);
				opts.maxHeight += opts.tolerance;

				$inr.empty();
				$inr.append(orgContent.clone(true));

				var after = false,
					trunc = false;

				if (conf.afterElement) {
					after = conf.afterElement.clone(true);
					conf.afterElement.remove();
				}
				if (test($inr, opts)) {
					if (opts.wrap == 'children') {
						trunc = children($inr, opts, after);
					} else {
						trunc = ellipsis($inr, $inr, opts, after);
					}
				}
				conf.isTruncated = trunc;
				return trunc;
			});
			$dot.bind('isTruncated.dot', function(e, fn) {
				var t = conf.isTruncated;
				if (typeof fn == 'function') {
					fn.call($tt0, t);
				}
				return t;
			});
			$dot.bind('originalContent.dot', function(e, fn) {
				var c = orgContent;
				if (typeof fn == 'function') {
					fn.call($tt0, c);
				}
				return c;
			});
			$dot.bind('destroy.dot', function(e) {
				e.stopPropagation();
				$dot.unwatch();
				$dot.unbind_events();
				$dot.empty();
				$dot.append(orgContent);
				$dot.data('dotdotdot', false);
			});
		};	//	/bind_events

		$dot.unbind_events = function() {
			$dot.unbind('.dot');
		};	//	/unbind_events

		$dot.watch = function() {
			$dot.unwatch();
			if (opts.watch == 'window') {
				$(window).bind('resize.dot', function() {
					if (watchInt) {
						clearInterval(watchInt);
					}
					watchInt = setTimeout(function() {
						$dot.trigger('update');
					}, 10);
				});
			} else {
				watchOrg = getSizes($dot);
				watchInt = setInterval(function() {
					var watchNew = getSizes($dot);
					if (watchOrg.width  != watchNew.width ||
						watchOrg.height != watchNew.height
					) {
						$dot.trigger('update');
						watchOrg = getSizes($dot);
					}
				}, 100);
			}
		};
		$dot.unwatch = function() {
			if (watchInt) {
				clearInterval(watchInt);
			}
		};

		var	orgContent	= $dot.contents(),
			opts 		= $.extend(true, {}, $.fn.dotdotdot.defaults, o),
			conf		= {},
			watchOrg	= {},
			watchInt	= null,
			$inr		= $dot.wrapInner('<'+opts.wrapper+' class="dotdotdot" />').children();

		conf.afterElement	= getElement(opts.after, $inr);
		conf.isTruncated	= false;

		$inr.css({
			'height'	: 'auto',
			'width'		: 'auto'
		});

		$dot.data('dotdotdot', true);
		$dot.bind_events();
		$dot.trigger('update');
		if (opts.watch) {
			$dot.watch();
		}

		return $dot;
	};



	//	public
	$.fn.dotdotdot.defaults = {
		'wrapper'	: 'div',
		'ellipsis'	: '... ',
		'wrap'		: 'word',
		'tolerance'	: 0,
		'after'		: null,
		'height'	: null,
		'watch'		: false,
		'debug'		: false
	};
	

	//	private
	function children($elem, o, after) {
		var $elements = $elem.children(),
			isTruncated = false;

		$elem.empty();

		for (var a = 0, l = $elements.length; a < l; a++) {
			var $e = $elements.eq(a);
			$elem.append($e);
			if (after) {
				$elem.append(after);
			}
			if (test($elem, o)) {
				$e.remove();
				isTruncated = true;
				break;
			} else {
				if (after) {
					after.remove();
				}
			}
		}
		return isTruncated;
	}
	function ellipsis($elem, $i, o, after) {
		var $elements = $elem.contents(),
			isTruncated = false;

		$elem.empty();

		var notx = 'table, thead, tbody, tfoot, tr, col, colgroup, object, embed, param, ol, ul, dl, select, optgroup, option, textarea, script, style';

		for (var a = 0, l = $elements.length; a < l; a++) {

			if (isTruncated) {
				break;
			}

			var e	= $elements[a],
				$e	= $(e);

			if (typeof e == 'undefined') {
				continue;
			}

			$elem.append($e);
			if (after) {
				var func = ($elem.is(notx)) ? 'after' : 'append';
				$elem[func](after);
			}
			if (e.nodeType == 3) {
				if (test($i, o)) {
					isTruncated = ellipsisElement($e, $i, o, after);
				}
			} else {
				isTruncated = ellipsis($e, $i, o, after);
			}

			if (!isTruncated) {
				if (after) {
					after.remove();
				}
			}
		}
		return isTruncated;
	}
	function ellipsisElement($e, $i, o, after) {
		var isTruncated	= false,
			e			= $e[0];

		if (typeof e == 'undefined') {
			return false;
		}

		var seporator	= (o.wrap == 'letter') ? '' : ' ',
			textArr		= getTextContent(e).split(seporator);

		setTextContent(e, textArr.join(seporator) + o.ellipsis);

		for (var a = textArr.length - 1; a >= 0; a--) {
			if (test($i, o)) {

				var end = getTextContent(e).length - (textArr[a].length + seporator.length + o.ellipsis.length),
					txt = (end > 0) ? getTextContent(e).substring(0, end) : '';

				setTextContent(e, txt + o.ellipsis);

			} else {
				isTruncated = true;
				break;
			}
		}


		if (!isTruncated) {
			var $w = $e.parent();
			$e.remove();
			$n = $w.contents().eq(-1);

			isTruncated = ellipsisElement($n, $i, o, after);
		}


		return isTruncated;
	}
	function test($i, o) {
		return $i.innerHeight() > o.maxHeight;
	}
	function getSizes($d) {
		return {
			'width'	: $d.innerWidth(),
			'height': $d.innerHeight()
		};
	}
	function setTextContent(e, content) {
		if (e.innerText) {
			e.innerText = content;
		} else if (e.nodeValue) {
			e.nodeValue = content;
		} else if (e.textContent) {
			e.textContent = content;
		}
	}
	function getTextContent(e) {
		if (e.innerText) {
			return e.innerText;
		} else if (e.nodeValue) {
			return e.nodeValue;
		} else if (e.textContent) {
			return e.textContent;
		} else {
			return "";
		}
	}
	function getElement(e, $i) {
		if (typeof e == 'undefined') {
			return false;
		}
		if (!e) {
			return false;
		}
		if (typeof e == 'string') {
			e = $(e, $i);
			return (e.length) ? e : false;
		}
		if (typeof e == 'object') {
			return (typeof e.jquery == 'undefined') ? false : e;
		}
		return false;
	}
	function getTrueInnerHeight($el) {
		var h = $el.innerHeight(),
			a = ['paddingTop', 'paddingBottom'];

		for (z = 0, l = a.length; z < l; z++) {
			var m = parseInt($el.css(a[z]));
			if (isNaN(m)) m = 0;
			h -= m;
		}
		return h;
	}
	function debug(d, m) {
		if (!d) return false;
		if (typeof m == 'string') m = 'dotdotdot: ' + m;
		else m = ['dotdotdot:', m];

		if (window.console && window.console.log) window.console.log(m);
		return false;
	}

})(jQuery);