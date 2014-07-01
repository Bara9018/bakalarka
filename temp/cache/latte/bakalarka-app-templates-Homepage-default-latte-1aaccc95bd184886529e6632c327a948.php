<?php
// source: C:\xampp\htdocs\bakalarka\app/templates/Homepage/default.latte

// prolog Latte\Macros\CoreMacros
list($_b, $_g, $_l) = $template->initialize('0133892101', 'html')
;
// prolog Latte\Macros\BlockMacros
//
// block content
//
if (!function_exists($_b->blocks['content'][] = '_lbe6513f8665_content')) { function _lbe6513f8665_content($_b, $_args) { foreach ($_args as $__k => $__v) $$__k = $__v
?><div class="btn-group">
    <a class="btn btn-primary" href="#"><i class="icon-user icon-white"></i> User</a>
    <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
    <ul class="dropdown-menu">
	<li><a href="#"><i class="icon-pencil"></i> Edit</a></li>
	<li><a href="#"><i class="icon-trash"></i> Delete</a></li>
	<li><a href="#"><i class="icon-ban-circle"></i> Ban</a></li>
	<li class="divider"></li>
	<li><a href="#"><i class="i"></i> Make admin</a></li>
    </ul>
</div>
<?php
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