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

/* themes/tara/templates/layout/page--front.html.twig */
class __TwigTemplate_b61a1e6c1813255f86195b7ba7c93a1eda9fb742535cf24727eeb63e8284ac07 extends \Twig\Template
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
        // line 11
        $this->loadTemplate("@tara/template-parts/header.html.twig", "themes/tara/templates/layout/page--front.html.twig", 11)->display($context);
        // line 12
        echo "<div id=\"main-wrapper\" class=\"main-wrapper\">
  <div class=\"\">
    ";
        // line 14
        if (($context["front_sidebar"] ?? null)) {
            // line 15
            echo "      <div class=\"main-container\">
    ";
        }
        // line 17
        echo "     
    <main id=\"front-main\" class=\"homepage-content page-content\" role=\"main\">
      <a id=\"main-content\" tabindex=\"-1\"></a>";
        // line 20
        echo "      ";
        $this->loadTemplate("@tara/template-parts/content_top.html.twig", "themes/tara/templates/layout/page--front.html.twig", 20)->display($context);
        // line 21
        echo "      ";
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "content", [], "any", false, false, true, 21)) {
            // line 22
            echo "\t\t\t\t";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "content", [], "any", false, false, true, 22), 22, $this->source), "html", null, true);
            echo "
\t\t\t";
        }
        // line 24
        echo "\t\t\t";
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "content_home", [], "any", false, false, true, 24)) {
            // line 25
            echo "\t\t\t\t<div class=\"homepage-content\">
\t\t\t\t\t";
            // line 26
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "content_home", [], "any", false, false, true, 26), 26, $this->source), "html", null, true);
            echo "
\t\t\t\t</div><!--/.homepage-content -->
\t\t\t";
        }
        // line 29
        echo "      ";
        $this->loadTemplate("@tara/template-parts/content_bottom.html.twig", "themes/tara/templates/layout/page--front.html.twig", 29)->display($context);
        // line 30
        echo "    </main>
    ";
        // line 31
        if (($context["front_sidebar"] ?? null)) {
            // line 32
            echo "      ";
            $this->loadTemplate("@tara/template-parts/left_sidebar.html.twig", "themes/tara/templates/layout/page--front.html.twig", 32)->display($context);
            // line 33
            echo "      ";
            $this->loadTemplate("@tara/template-parts/right_sidebar.html.twig", "themes/tara/templates/layout/page--front.html.twig", 33)->display($context);
            // line 34
            echo "      </div> ";
            // line 35
            echo "    ";
        }
        // line 36
        echo "  </div> <!--/.container -->
</div><!-- /main-wrapper -->
";
        // line 38
        $this->loadTemplate("@tara/template-parts/footer.html.twig", "themes/tara/templates/layout/page--front.html.twig", 38)->display($context);
    }

    public function getTemplateName()
    {
        return "themes/tara/templates/layout/page--front.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  102 => 38,  98 => 36,  95 => 35,  93 => 34,  90 => 33,  87 => 32,  85 => 31,  82 => 30,  79 => 29,  73 => 26,  70 => 25,  67 => 24,  61 => 22,  58 => 21,  55 => 20,  51 => 17,  47 => 15,  45 => 14,  41 => 12,  39 => 11,);
    }

    public function getSourceContext()
    {
        return new Source("", "themes/tara/templates/layout/page--front.html.twig", "C:\\wamp64\\www\\ncm\\update\\Re-desigen\\web\\themes\\tara\\templates\\layout\\page--front.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("include" => 11, "if" => 14);
        static $filters = array("escape" => 22);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['include', 'if'],
                ['escape'],
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
