Articles = JAK.ClassMaker.makeClass({
	NAME : "Articles",
	VERSION : 1.0
});

Articles.prototype.$constructor = function() {
	this.elm = {};
	this.elm._removeLinks = JAK.query(".delete-article");

	this._ec = [];
	for (var i=0, j=this.elm._removeLinks.length; i<j; i++) {
		this._ec.push(JAK.Events.addListener(this.elm._removeLinks[i], "click", this, "_confirm"));
	}
};

Articles.prototype._confirm = function(e, elm) {
	JAK.Events.cancelDef(e);

	var popup = new Popup({
		popUpContent: {
			title: elm.title,
			text: "Opravdu si přejete smazat tento článek?",
			buttons: [
				{ title: "Ano", callback: elm.href },
				{ title: "Ne", closeOnClick: true }
			]
		}
	});
	popup.open();
};
