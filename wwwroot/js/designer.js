Designer = JAK.ClassMaker.makeClass({
	NAME : "Designer",
	VERSION : 1.4
});

/**
 * @constructor
 */
Designer.prototype.$constructor = function() {
	this._container = JAK.gel("container");

	this._lastContHeight;

	this._timeoutLimit = 100;

	this._ec = [];

	this._timeout();
	this._addSidebarShadow();
	this._addListeners();
};

/**
 * Navěsí události
 */
Designer.prototype._addListeners = function() {
	this._ec.push(JAK.Events.addListener(window, "resize", this, "resize"));
};

/**
 * Timeout pro znovuvykreslování layoutu
 */
Designer.prototype._timeout = function() {
	this.resize();
	setTimeout(this._timeout.bind(this), this._timeoutLimit);
};

/**
 * Přepočítá a změní velikost kontejneru
 */
Designer.prototype.resize = function(e, elm) {
	this._container.style.minHeight = (window.innerHeight - 190) + "px";
	this._container.style.height = "auto";
	this._container.style.height = this._container.offsetHeight + "px";
};

/**
 * Přidá stín z postranního sloupce do subtitle
 */
Designer.prototype._addSidebarShadow = function() {
	var subtitle = JAK.query(".subtitle")[0];
	if (!subtitle || JAK.query(".subtitle .shadow")[0]) return;

	subtitle.appendChild(
		JAK.mel("span", { className: "shadow" }, { height: subtitle.offsetHeight + "px" })
	);
};
