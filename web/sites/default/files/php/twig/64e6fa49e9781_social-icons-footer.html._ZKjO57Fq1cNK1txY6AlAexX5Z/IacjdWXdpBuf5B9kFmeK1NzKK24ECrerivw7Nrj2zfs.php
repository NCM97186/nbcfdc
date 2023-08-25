<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* @tara/template-parts/social-icons-footer.html.twig */
class __TwigTemplate_af1a6f324bd4422594bdba00dda845ce38b4d04a6220739b8222ddbdee50e026 extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<div>
<li><span style=\"font-weight:bold;\">CIN:</span>&nbsp;U74899DL1992NPL047146</li>&nbsp;
|&nbsp;&nbsp;<li><span style=\"font-weight:bold;\">GSTIN:</span>&nbsp;07AABCN8953B1ZI</li>
</div>

<div>
<li><span style=\"font-weight:bold;\">Total Visitor:</span>&nbsp;0111</li>&nbsp;
|&nbsp;
<li><span style=\"font-weight:bold;\">Last Update:</span>&nbsp; 25 July 2023</li>
</div>


";
    }

    public function getTemplateName()
    {
        return "@tara/template-parts/social-icons-footer.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  39 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "@tara/template-parts/social-icons-footer.html.twig", "C:\\wamp64\\www\\ncm\\update\\Re-desigen\\web\\themes\\tara\\templates\\template-parts\\social-icons-footer.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array();
        static $filters = array();
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                [],
                [],
                []
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->source);

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }
}
