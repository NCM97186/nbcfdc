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

/* @tara/template-parts/header.html.twig */
class __TwigTemplate_d78e6674ac0d47f87c7c6c20b210b9613575defaa963d1deb6dc10dfbb9b578b extends \Twig\Template
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
        echo "<!-- Start: Header -->
<header id=\"header\">
  ";
        // line 3
        if ((twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "header_top", [], "any", false, false, true, 3) || ($context["all_icons_show"] ?? null))) {
            // line 4
            echo "    ";
            $this->loadTemplate("@tara/template-parts/header_top.html.twig", "@tara/template-parts/header.html.twig", 4)->display($context);
            // line 5
            echo "  ";
        }
        // line 6
        echo "  <div class=\"header\">
     <div class=\"\" style=\"padding:0 80px;\">
      <div class=\"container-fluid pl-0\">
        ";
        // line 9
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "site_branding", [], "any", false, false, true, 9)) {
            // line 10
            echo "         <div class=\"row\">
        <div class=\"col-md-6\">
            <img style=\"height: 110px;\" src=\"";
            // line 12
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($this->sandbox->ensureToStringAllowed(($context["base_path"] ?? null), 12, $this->source) . $this->sandbox->ensureToStringAllowed(($context["directory"] ?? null), 12, $this->source)), "html", null, true);
            echo "/images/Main_NBCGDC.png\">
         </div>
         

<div class=\"col-md-6\">
          <div class=\"d-flex p-2\" style=\"float:right;\">  
    <div class=\"p-1\">  <img  style=\"height:75px;\" src=\"";
            // line 18
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($this->sandbox->ensureToStringAllowed(($context["base_path"] ?? null), 18, $this->source) . $this->sandbox->ensureToStringAllowed(($context["directory"] ?? null), 18, $this->source)), "html", null, true);
            echo "/images/logo-amrit.jpg\"></div>
    <div class=\"p-1\">   <img  style=\"height:75px;\" src=\"";
            // line 19
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($this->sandbox->ensureToStringAllowed(($context["base_path"] ?? null), 19, $this->source) . $this->sandbox->ensureToStringAllowed(($context["directory"] ?? null), 19, $this->source)), "html", null, true);
            echo "/images/G20_India_2023.png\"></div>
    <div class=\"p-1\"> <img  style=\"height:75px;\" src=\"";
            // line 20
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ($this->sandbox->ensureToStringAllowed(($context["base_path"] ?? null), 20, $this->source) . $this->sandbox->ensureToStringAllowed(($context["directory"] ?? null), 20, $this->source)), "html", null, true);
            echo "/images/logo2.png\"></div>
  </div>
</div>
         </div>
         </div>
          \t
          <!--/.site-branding -->
        ";
        }
        // line 27
        echo " <!--/.end if for site_branding -->
        ";
        // line 28
        if ((twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "search_box", [], "any", false, false, true, 28) || twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "primary_menu", [], "any", false, false, true, 28))) {
            // line 29
            echo "        </div>
          <div class=\"header-right justify-content-center\">
            <!-- Start: primary menu region -->
            ";
            // line 32
            if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "primary_menu", [], "any", false, false, true, 32)) {
                // line 33
                echo "            <div class=\"mobile-menu\">
              <i class=\"fa fa-bars\" aria-hidden=\"true\"></i>
            </div><!-- /mobile-menu -->
            <div class=\"primary-menu-wrapper\">
              <div class=\"menu-wrap\">
                <div class=\"close-mobile-menu\">X</div>
                ";
                // line 39
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "primary_menu", [], "any", false, false, true, 39), 39, $this->source), "html", null, true);
                echo "
              </div>
            </div><!-- /primary-menu-wrapper -->
            ";
            }
            // line 42
            echo "<!-- end if for page.primary_menu -->
            ";
            // line 43
            if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "search_box", [], "any", false, false, true, 43)) {
                // line 44
                echo "             
              <!--/.full-page-search -->
            ";
            }
            // line 46
            echo " <!-- end if for page.search_box -->
          </div> <!--/.header-right -->
        ";
        }
        // line 48
        echo "<!-- end if for page.search_box or  page.primary_menu -->
      </div> <!--/.header-container -->
    </div> <!--/.container -->
  </div><!-- /.header -->
</header>
<!-- End: Header -->
";
    }

    public function getTemplateName()
    {
        return "@tara/template-parts/header.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  132 => 48,  127 => 46,  122 => 44,  120 => 43,  117 => 42,  110 => 39,  102 => 33,  100 => 32,  95 => 29,  93 => 28,  90 => 27,  79 => 20,  75 => 19,  71 => 18,  62 => 12,  58 => 10,  56 => 9,  51 => 6,  48 => 5,  45 => 4,  43 => 3,  39 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "@tara/template-parts/header.html.twig", "C:\\wamp64\\www\\ncm\\update\\Re-desigen\\web\\themes\\tara\\templates\\template-parts\\header.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("if" => 3, "include" => 4);
        static $filters = array("escape" => 12);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['if', 'include'],
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
