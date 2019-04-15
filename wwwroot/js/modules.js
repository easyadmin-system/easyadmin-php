Modules = JAK.ClassMaker.makeClass({
	NAME : "Modules",
	VERSION : "1.0"
});

/**
 * @constructor
 * @overview Stará se o přidávání modulů na stránku
 * @author Jan Elznic, https://www.janelznic.cz
 * @param {object} opt Konfigurační objekt
 */
Modules.prototype.$constructor = function(opt, content, designer) {
	/* Výchozí hodnoty nastavení */
	this._opt = {
		
	};

	/* Načtení předané konfigurace */
	for (var p in opt) {
		this._opt[p] = opt[p];
	}

	/* Cache proměnná pro všechen možný bordel */
	this._cache = {};

	/* Sem budeme ukládat HTML-DOM nody */
	this._dom = {};

	/* Incisializujeme elementy */
	var elmsId = ["addLinksCont", "modules", "content", "form", "save"];
	for (var id, i=0, len = elmsId.length; len > i; i++) {
		id = elmsId[i];
		this._dom[id] = JAK.gel(id);
	}

	/* Linky pro přidání modulu */
	this._addLinks = JAK.query("li a", this._dom.addLinksCont);

	/* Aktuální moduly na stránce */
	this._modules = [];

	/* Obsah na stránce */
	this._content = content;

	/* Instance tříd */
	this._designer = designer;

	/* Poslední použitý identifikátor textarea */
	this._textAreaId;

	this._visualizeContent();

	/* Eventy */
	this._ec = [];
	this._addListeners();
};

/**
 * Přidá do DOM stránky (visualizuje) moduly s obsahem
 */
Modules.prototype._visualizeContent = function() {
	for (var mod, len=this._content.length, i=0; len>i; i++) {
		mod = this._content[i];
		this._addModule(mod.type, mod.value);
	}
};

/**
 * Navěšení posluchačů
 */
Modules.prototype._addListeners = function() {
	/*
	for (var i=0, len = this._addLinks.length; len > i; i++) {
		this._ec.push(JAK.Events.addListener(this._addLinks[i], "click", this, "_newModule"));
	}
	*/
	this._ec.push(JAK.Events.addListener(this._dom.save, "click", this, "_submit"));
	this._ec.push(JAK.Events.addListener(this._dom.form, "submit", this, "_submit"));
};

/**
 * Potvrzení odeslání formuláře s obsahem
 */
Modules.prototype._submit = function(e, elm) {
	if (e) JAK.Events.cancelDef(e);
	this._setContent();
	this._dom.content.value = JSON.stringify(this._content);
	this._dom.form.submit();
};

/**
 * Nastaví obsah modulů do skrytého inputu ve formuláři
 */
Modules.prototype._setContent = function() {
	var content = [];
	for (var modCont, type, position, i=0, len=this._modules.length; len>i; i++) {
		modCont = this._modules[i].dom;
		type = modCont.getAttribute("data-type");
		position = modCont.getAttribute("data-position");
		switch (type) {
			case "text":
				content.push({
					type: type,
					position: position,
					value: this._modules[i].data.wysiwyg.editor.getContent()
				});
				break;
		}
	}
	this._content = content;
};

/**
 * Přidá do stránky nový modul
 */
Modules.prototype._newModule = function(e, elm) {
	if (e) JAK.Events.cancelDef(e);
	var type = elm.getAttribute("data-type");

	this._addModule(type);
};

/**
 * Přidá do stránky nový modul
 */
Modules.prototype._addModule = function(type, content) {
	var dom = this._buildDOM(type, content);

	switch (type) {
		case "text":
			var data = { wysiwyg: new WYSIWYG(this._textAreaId, this._opt.tinymceOpt) };
			break;
		default:
			return;
	}

	this._modules.push({ dom: dom, type: type, data: data });
	this._designer.resize();
};

/**
 * Vybuildí DOM strukturu modulu
 */
Modules.prototype._buildDOM = function(type, content) {

	var modCont = this._buildHead(type);

	switch (type) {
		/* Textový obsah */
		case "text":
			var modTitle = this._cache.modTitle;
			modTitle.innerHTML = "Textový obsah";
			this._textAreaId = "textarea_" + new Date().getTime();
			var textarea = JAK.mel("textarea", { id: this._textAreaId, innerHTML: content });
			this._cache.inside.appendChild(textarea);
			break;
		case "error":
			var errCont = JAK.mel("div", { className: "notice" });
			var inside = JAK.mel("div", { className: "window" });
			JAK.DOM.addClass(inside, "error");
			inside.innerHTML = "<h2>" + content + "</h2>";
			errCont.appendChild(inside);
			this._dom.modules.appendChild(errCont);
			modCont = errCont;
		default:
			return;
	}

	//this._addBoxIcons();
	return modCont;
};

/**
 * Vybuildí hlavičku modulu a připojí do DOM
 */
Modules.prototype._buildHead = function(type) {
	if (type == "error") return;

	var modCont = JAK.mel("div", { className: "modCont" });
	var position = JAK.query(".modCont", this._dom.modules).length;
	modCont.setAttribute("data-position", position);
	modCont.setAttribute("data-type", type);
	JAK.DOM.addClass(modCont, "titledBox");

	var modTitle = JAK.mel("h2");
	modCont.appendChild(modTitle);

	var inside = JAK.mel("div", { className: "inside" });
	JAK.DOM.addClass(inside, type);
	modCont.appendChild(inside);
	this._cache.modTitle = modTitle;
	this._cache.inside = inside;
	this._dom.modules.appendChild(modCont);

	return modCont;
};

/**
 * Přidá ikonky pro manipulaci s boxem (změna pozice, odstranění)
 */
Modules.prototype._addBoxIcons = function(type) {
	if (type == "error") return;

	var boxIcons = JAK.mel("ul", { className: "box-icons" });
	var modTitle = this._cache.modTitle;
	modTitle.appendChild(boxIcons);
	var liMove = JAK.mel("li");
	liMove.appendChild(JAK.mel("a", { href: "#", title: "Přesunout", className: "move" }));
	boxIcons.appendChild(liMove);
	var liRemove = JAK.mel("li");
	liRemove.appendChild(JAK.mel("a", { href: "#", title: "Odstranit", className: "remove" }));
	boxIcons.appendChild(liRemove);
};
