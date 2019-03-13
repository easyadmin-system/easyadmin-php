Submiter = JAK.ClassMaker.makeClass({
	NAME : "Submiter",
	VERSION : 1.0
});

Submiter.prototype.$constructor = function(formId, buttonId) {
	this._form = JAK.gel(formId);
	var button = JAK.gel(buttonId);

	this._ec = [];
	this._ec.push(JAK.Events.addListener(button, "click", this, "_submit"));
};

Submiter.prototype._submit = function(e, elm) {
	JAK.Events.cancelDef(e);
	this._form.submit();
};
