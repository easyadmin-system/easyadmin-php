Languages = {};

Languages.List = JAK.ClassMaker.makeClass({
	NAME : "Languages.List",
	VERSION : 1.0
});

Languages.List.prototype.$constructor = function() {
	this.elm = {};
	this.elm._removeLinks = JAK.query(".remove-language");

	this._ec = [];
	for (var i=0, j=this.elm._removeLinks.length; i<j; i++) {
		this._ec.push(JAK.Events.addListener(this.elm._removeLinks[i], "click", this, "_confirm"));
	}
};

Languages.List.prototype._confirm = function(e, elm) {
	JAK.Events.cancelDef(e);

	var popup = new Popup({
		popUpContent: {
			title: elm.title,
			text: "Opravdu si přejete odebrat tento jazyk?<br />"
				+ "<b class=\"red\">POZOR!</b> Odebráním tohoto jazyka se smaže i všechen obsah související s tímto jazykem!",
			buttons: [
				{ title: "Ano", callback: elm.href },
				{ title: "Ne", closeOnClick: true }
			]
		}
	});
	popup.open();
};
