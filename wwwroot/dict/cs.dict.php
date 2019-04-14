<?php
# Správa uživatelů
$dict = array(
"userNameRulesTooltip" => "Uživatelské jméno musí splňovat následující podmínky:<br /><small>- délka 3 až 20 znaků<br />- začíná vždy písmenem nebo číslicí<br />- neobsahuje diakritiku<br />- smí obsahovat znaky a-z, A-Z, 0-9, tečku, pomlčku a podtržítko<br />- nesmí končit tečkou</small>",
"userAuthorityTooltip" => "<b>běžný uživatel (0):</b><br /><small>- standardně nemá žádná oprávnění</small><br /><b>redaktor (1):</b><br /><small>- smí psát články, čekají na schválení administrátorem</small><br /><b>administrátor (2):</b><br /><small>- může měnit obsah webu</small><br /><b>hlavní administrátor (3):</b><br /><small>- může měnit obsah webu, přidávat a mazat uživatelské účty</small>",
"userStarTooltip" => "Hvězdičky slouží k rozlišování skupin uživatelů",
"userEmailTooltip" => "Slouží například pro zaslání nového hesla, nikde se nezveřejňuje",
"internalError" => "<h2>Došlo k neznámé vnitřní chybě.</h2><p>Zkuste to prosím později nebo kontaktujte správce webu.</p>",
"userPassRulesToolTip" => "Heslo musí obsahovat alespoň 5 znaků",
"wysiwygEditorTooltip" => "WYSIWYG editor slouží k usnadnění práce s formátováním textu a není při jeho použití nutná znalost HTML zdrojového kódu.",
"pass" => "Heslo musí mít alespoň 5 znaků. Je povoleno používat jakékoliv znaky na klávesnici.",
"pass2" => "Sem prosím heslo přepište znovu. Jedná se o kontrolu překlepu.",

# Stránky
"pagesSubtitleSearchTT" => "Do vyhledávacího pole můžete zadat titulek stránky, její URL adresu nebo klíčová slova, podle kterých chcete hledat.",
"pagesSubtitlePageTitle" => "Hlavní titulek stránky. Zobrazuje se na stránce jako nadpis nejvyšší úrovně, v titulku prohlížeče i jako hlavní odkaz u výsledku hledání ve vyhledávačích.",
"pagesSubtitlePageURL" => "URL adresa je adresa v internetovém prohlížeči, pod kterou se stránka zobrazí. Zadávejte v absolutním tvaru, tedy vč. http:// na začátku.",

# Skupiny a oprávnění
"groups" => array(
	"nameRulesTooltip" => "Název skupiny by měl být stručný.",
	"sectionsAuthToolTip" => "<b>přispěvatel (1)</b><br /><small>- Může psát články, čeká na schválení redaktorem.</small><br /><b>redaktor (2)</b><br /><small>- Může psát články, ihned je publikovat, schvalovat články přispěvatelů a upravovat nebo mazat články ostatních autorů v rámci dané rubriky.</small><br /><b>šéfredaktor (3)</b><br /><small>- Může psát články, ihned je publikovat, schvalovat články přispěvatelů, upravovat nebo mazat články ostatních autorů a spravovat všechny rubriky.</small>",
	"categoriesAuthToolTip" => "<b>správce kategorie (1)</b><br /><small>- Může upravovat stránky nebo zakládat nové. Změny čekají na schválení hlavním správcem kategorie.</small><br /><b>hlavní správce kategorie (2)</b><br /><small>- Může upravovat stránky, zakládat nové. Schvaluje změny v obsahu stránek ostatních správců v rámci kategorie.</small>"
),


# Obecná nastavení systému
"sysconfig" => array(
	"site_title" => "Titulek webu v záhlaví okna prohlížeče",
	"site_description" => "Popis webu pro vyhledávače (často se vyskytuje ve výsledcích vyhledávání)",
	"site_keywords" => "Obecná klíčová slova pro web (oddělujte čárkou)",
	"site_root_url" => "Kořenová URL webu",
	"system_lock" => "Uzamknout přihlašování do systému pro administrátory na úrovni 2 a nižší",
	"login_timeout" => "Odhlašovat automaticky ze systému pro intervalu (v minutách)",
	"updates_check" => "Zjišťovat nové verze a aktualizace systému",
	"updates_check_interval" => "Interval zjišťování nových verzí a aktualizací systému (ve dnech)"
)
);
?>
