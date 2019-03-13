Tooltip = JAK.ClassMaker.makeClass({
	NAME : "Tooltip",
	VERSION : 1.1
});

/**
 * @constructor
 **/
Tooltip.prototype.$constructor = function() {
	this._tooltips = [];

	this._init();

	this._buildAllBubbles();

	this._ec = [];
	this._addListeners();
};

/**
 * Projde všecky elementy na stránce s danou classou
 **/
Tooltip.prototype._init = function() {
	var tooltipNodes = JAK.DOM.getElementsByClass("tooltip", document);

	var tooltip;
	var link;
	var allLinksCol;
	var title;
	var msgSpan;

	for (var i=0; tooltipNodes.length > i; i++) {
		allLinksCol = tooltipNodes[i].getElementsByTagName("a");

		/* Viditelný text */
		link = JAK.DOM.arrayFromCollection(allLinksCol)[0];

		/* Text do bubliny */
		msgSpan = JAK.DOM.getElementsByClass("msg", tooltipNodes[i])[0];
		msg = msgSpan.innerHTML;

		/* Skryjeme text do bubliny tak, aby ho mohli přečíst jen slepí */
		JAK.DOM.addClass(msgSpan, "blind");

		tooltip = {
			node: tooltipNodes[i],
			link: link,
			msg: msg
		};

		this._tooltips.push(tooltip);
	};
};

/**
 * Navěsí posluchače
 **/
Tooltip.prototype._addListeners = function() {
	for (var i=0; this._tooltips.length > i; i++) {
		this._ec.push(JAK.Events.addListener(this._tooltips[i].node, "mouseover", this,"_showBubble"));
		this._ec.push(JAK.Events.addListener(this._tooltips[i].node, "mouseout", this,"_hideBubble"));
	};
};

/**
 * Ukáže bublinu
 **/
Tooltip.prototype._showBubble = function(e, elm) {
	var index = this._getIndexOfElm(elm);
	this._tooltips[index].bubble.style.display = "block";
	this._setNewPosition(index);
};

/**
 * Skryje bublinu
 **/
Tooltip.prototype._hideBubble = function(e, elm) {
	var index = this._getIndexOfElm(elm);
	this._tooltips[index].bubble.style.display = "none";
};

/**
 * Vrátí index bubliny z pole s tooltipy
 **/
Tooltip.prototype._getIndexOfElm = function(elm) {
	var nodes = [];
	for (var i=0; this._tooltips.length > i; i++) {
		nodes.push(this._tooltips[i].node);
	};
	return nodes.indexOf(elm);
};

/**
 * Vybuildí DOM všech bublin
 **/
Tooltip.prototype._buildAllBubbles = function(e, elm) {
	var link;
	var wrap;
	var top;
	var middle;
	var bottom;
	var paragraph;

	for (var i=0; this._tooltips.length > i; i++) {
		link = this._tooltips[i].node;

		/* Obal bubliny */
		wrap = JAK.mel("div", { className: "bubble" } );
		link.appendChild(wrap);

		/* Horní zaoblené rohy */
		top = JAK.mel("div", { className: "top" } );
		wrap.appendChild(top);

		/* Prostředek s ocáskem */
		middle = JAK.mel("div", { className: "middle" } );
		wrap.appendChild(middle);

		/* Odstavec */
		paragraph = JAK.mel("p", { innerHTML: this._tooltips[i].msg } );
		middle.appendChild(paragraph);

		/* Spodní zaoblené rohy */
		bottom = JAK.mel("div", { className: "bottom" } );
		wrap.appendChild(bottom);

		/* Přidá do objektu s informacemi a nody o tooltipu obal bubliny */
		this._tooltips[i].bubble = wrap;

		/* Nastaví pozici pro bublinu */
		this._setNewPosition(i);

		/* Skryje bublinu */
		wrap.style.display = "none";
	};
};

/**
 * Nastaví pozici pro zobrazení bubliny
 **/
Tooltip.prototype._setNewPosition = function(index) {
	var bubble = this._tooltips[index].bubble;
	var link = this._tooltips[index].link;

	/* Pozice bubliny... */
	bubble.style.top = (link.offsetTop - (bubble.offsetHeight / 2) + 10) + "px";
	var pos = (link.offsetLeft + link.offsetWidth);
	if (pos >= (window.innerWidth - bubble.offsetWidth)) {
		bubble.style.left = (link.offsetLeft - bubble.offsetWidth) + "px";
		JAK.DOM.addClass(bubble, "toleft");
		JAK.DOM.removeClass(bubble, "toright");
	} else {
		bubble.style.left = pos + "px";
		JAK.DOM.addClass(bubble, "toleft");
		JAK.DOM.addClass(bubble, "toright");
	}
};
