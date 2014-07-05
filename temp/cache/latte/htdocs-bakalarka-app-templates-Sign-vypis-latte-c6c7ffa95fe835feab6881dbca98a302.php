<?php
// source: C:\xampp\htdocs\bakalarka\app/templates/Sign/vypis.latte

// prolog Latte\Macros\CoreMacros
list($_b, $_g, $_l) = $template->initialize('5411954515', 'html')
;
// prolog Latte\Macros\BlockMacros
//
// block content
//
if (!function_exists($_b->blocks['content'][] = '_lb7f234924e2_content')) { function _lb7f234924e2_content($_b, $_args) { foreach ($_args as $__k => $__v) $$__k = $__v
?><div id="table">
    <table id="table_users">
	<th>Meno</th>
	<th>Priezvisko</th>
	<th>Email</th>
<?php $iterations = 0; foreach ($person as $riadok) { ?>
	<tr>
	    <td><?php echo Latte\Runtime\Filters::escapeHtml($riadok->firstname, ENT_NOQUOTES) ?></td>
	    <td><?php echo Latte\Runtime\Filters::escapeHtml($riadok->lastname, ENT_NOQUOTES) ?></td>
	    <td><?php echo Latte\Runtime\Filters::escapeHtml($riadok->email, ENT_NOQUOTES) ?></td>
	</tr>
<?php $iterations++; } ?>
    </table>
</div><?php
}}

//
// end of blocks
//

// template extending

$_l->extends = empty($_g->extended) && isset($_control) && $_control instanceof Nette\Application\UI\Presenter ? $_control->findLayoutTemplateFile() : NULL; $_g->extended = TRUE;

if ($_l->extends) { ob_start();}

// prolog Nette\Bridges\ApplicationLatte\UIMacros

// snippets support
if (empty($_l->extends) && !empty($_control->snippetMode)) {
	return Nette\Bridges\ApplicationLatte\UIMacros::renderSnippets($_control, $_b, get_defined_vars());
}

//
// main template
//
if ($_l->extends) { ob_end_clean(); return $template->renderChildTemplate($_l->extends, get_defined_vars()); }
call_user_func(reset($_b->blocks['content']), $_b, get_defined_vars()) ; 