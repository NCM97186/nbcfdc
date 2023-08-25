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

/* @tara/template-parts/header_top.html.twig */
class __TwigTemplate_2dee67e091acb0e5f27b7d75d0110916d5986c6d7424ab1a3ca943e3754b9e7a extends \Twig\Template
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
        echo "
<div class=\"header-top\">
  <div class=\"container-fluid\">
    <div class=\"header-top-container\">
      ";
        // line 5
        if (($context["all_icons_show"] ?? null)) {
            // line 6
            echo "        <div class=\"header-top-right header-top-block\">
          ";
            // line 7
            $this->loadTemplate("@tara/template-parts/social-icons.html.twig", "@tara/template-parts/header_top.html.twig", 7)->display($context);
            // line 8
            echo "        </div><!--/.header-top-left -->
      ";
        }
        // line 10
        echo "      ";
        if (($context["all_icons_show"] ?? null)) {
            // line 11
            echo "        <div class=\"header-top-right header-top-block\">
          ";
            // line 12
            $this->loadTemplate("@tara/template-parts/social-icons2.html.twig", "@tara/template-parts/header_top.html.twig", 12)->display($context);
            // line 13
            echo "        </div> <!--/.header-top-right -->
      ";
        }
        // line 15
        echo "    </div> <!--/.header-top-container -->
  </div> <!--/.container -->
</div> <!--/.header-top -->
</div>

";
    }

    public function getTemplateName()
    {
        return "@tara/template-parts/header_top.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  68 => 15,  64 => 13,  62 => 12,  59 => 11,  56 => 10,  52 => 8,  50 => 7,  47 => 6,  45 => 5,  39 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "@tara/template-parts/header_top.html.twig", "C:\\wamp64\\www\\ncm\\update\\Re-desigen\\web\\themes\\tara\\templates\\template-parts\\header_top.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("if" => 5, "include" => 7);
        static $filters = array();
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['if', 'include'],
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
