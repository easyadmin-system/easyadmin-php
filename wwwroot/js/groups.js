Groups = {};

Groups.Switcher = JAK.ClassMaker.makeClass({
	NAME : "Groups.Switcher",
	VERSION : 1.0
});

Groups.Switcher.prototype.$constructor = function() {
	this._elm = {};
	this._elm.modCont = JAK.gel("privileges");
	this._elm.modules = JAK.query(".module", this._elm.modCont);
	this._elm.selects = [];
	this._elm.sets = [];

	for (var module, i=0, j=this._elm.modules.length; i<j; i++) {
		module = this._elm.modules[i];
		this._elm.selects.push(JAK.query("select", module)[0]);
		this._elm.sets.push(JAK.query("span.set", module));
	}

	this._ec = [];
	this._addListeners();
};

Groups.Switcher.prototype._addListeners = function() {
	for (var i=0, j=this._elm.selects.length; i<j; i++) {
		this._ec.push(JAK.Events.addListener(this._elm.selects[i], "change", this, "_switchSet"));
	}
};

Groups.Switcher.prototype._switchSet = function(e, elm) {
	var selectIndex = this._elm.selects.indexOf(elm);
	this._hideAllSets(selectIndex);
	this._showSet(selectIndex, elm.selectedIndex);
};

Groups.Switcher.prototype._hideAllSets = function(selectIndex) {
	for (var i=0, j=this._elm.sets[selectIndex].length; i<j; i++) {
		JAK.DOM.addClass(this._elm.sets[selectIndex][i], "nodisplay");
	}
};

Groups.Switcher.prototype._showSet = function(selectIndex, setIndex) {
	JAK.DOM.removeClass(this._elm.sets[selectIndex][setIndex], "nodisplay");
};

Groups.MemberList = JAK.ClassMaker.makeClass({
	NAME : "Groups.MemberList",
	VERSION : 1.0
});

Groups.MemberList.prototype.$constructor = function() {
	this.elm = {};
	this.elm._removeLinks = JAK.query(".remove-member");

	this._ec = [];
	for (var i=0, j=this.elm._removeLinks.length; i<j; i++) {
		this._ec.push(JAK.Events.addListener(this.elm._removeLinks[i], "click", this, "_confirm"));
	}
};

Groups.MemberList.prototype._confirm = function(e, elm) {
	JAK.Events.cancelDef(e);

	var popup = new Popup({
		popUpContent: {
			title: elm.title,
			text: "Opravdu si přejete odebrat tohoto člena?",
			buttons: [
				{ title: "Ano", callback: elm.href },
				{ title: "Ne", closeOnClick: true }
			]
		}
	});
	popup.open();
};
