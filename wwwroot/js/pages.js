Pages = {};

Pages.PageList = JAK.ClassMaker.makeClass({
	NAME : "Pages.PageList",
	VERSION : 1.0
});

Pages.PageList.prototype.$constructor = function() {
	this.elm = {};
	this.elm._removeLinks = JAK.query(".delete-page");

	this._ec = [];
	for (var i=0, j=this.elm._removeLinks.length; i<j; i++) {
		this._ec.push(JAK.Events.addListener(this.elm._removeLinks[i], "click", this, "_confirm"));
	}
};

Pages.PageList.prototype._confirm = function(e, elm) {
	JAK.Events.cancelDef(e);

	var popup = new Popup({
		popUpContent: {
			title: elm.title,
			text: "Opravdu si přejete smazat tuto stránku?",
			buttons: [
				{ title: "Ano", callback: elm.href },
				{ title: "Ne", closeOnClick: true }
			]
		}
	});
	popup.open();
};
