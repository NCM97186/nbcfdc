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

/* @tara/template-parts/footer.html.twig */
class __TwigTemplate_5e0f8f3871a53a25ad91f07b279bc8710ef99bb8ddb0f0b44378f873c39542ad extends \Twig\Template
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
        echo "<!-- Start: Footer -->
<footer id=\"footer\">
  <div class=\"footer\">
    <div class=\"container\">
      ";
        // line 5
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_top", [], "any", false, false, true, 5)) {
            // line 6
            echo "        <section class=\"footer-top\">
          ";
            // line 7
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_top", [], "any", false, false, true, 7), 7, $this->source), "html", null, true);
            echo "
        </section>
      ";
        }
        // line 9
        echo "<!-- /footer-top -->
      ";
        // line 10
        if ((((twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_first", [], "any", false, false, true, 10) || twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_second", [], "any", false, false, true, 10)) || twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_third", [], "any", false, false, true, 10)) || twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_fourth", [], "any", false, false, true, 10))) {
            // line 11
            echo "       <section class=\"footer-blocks\">
       
        ";
            // line 13
            if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_first", [], "any", false, false, true, 13)) {
                // line 14
                echo "          <div class=\"footer-block\">
            ";
                // line 15
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_first", [], "any", false, false, true, 15), 15, $this->source), "html", null, true);
                echo "
          </div>
          <p>jitu</p>
        ";
            }
            // line 18
            echo "<!--/footer-first -->
        ";
            // line 19
            if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_second", [], "any", false, false, true, 19)) {
                // line 20
                echo "          <div class=\"footer-block\">
            ";
                // line 21
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_second", [], "any", false, false, true, 21), 21, $this->source), "html", null, true);
                echo "
          </div>
        ";
            }
            // line 23
            echo "<!--/footer-second -->
        ";
            // line 24
            if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_third", [], "any", false, false, true, 24)) {
                // line 25
                echo "          <div class=\"footer-block\">
            ";
                // line 26
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_third", [], "any", false, false, true, 26), 26, $this->source), "html", null, true);
                echo "
          </div>
        ";
            }
            // line 28
            echo "<!--/footer-third -->
        ";
            // line 29
            if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_fourth", [], "any", false, false, true, 29)) {
                // line 30
                echo "          <div class=\"footer-block\">
            ";
                // line 31
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_fourth", [], "any", false, false, true, 31), 31, $this->source), "html", null, true);
                echo "
          </div>
        ";
            }
            // line 33
            echo "<!--/footer-fourth -->
       </section> <!--/footer-blocks -->
     ";
        }
        // line 35
        echo " ";
        // line 36
        echo "     ";
        if ((($context["copyright_text"] ?? null) || ($context["all_icons_show"] ?? null))) {
            // line 37
            echo "      <section class=\"footer-bottom-middle\">
        
        <!-- end if for copyright -->
        ";
            // line 40
            if (($context["all_icons_show"] ?? null)) {
                // line 41
                echo "          <div class=\"footer-bottom-middle-right justify-content-between d-flex w-100\" style=\"font-size:14px;\">
            ";
                // line 42
                $this->loadTemplate("@tara/template-parts/social-icons-footer.html.twig", "@tara/template-parts/footer.html.twig", 42)->display($context);
                // line 43
                echo "          </div>
        ";
            }
            // line 44
            echo " <!-- end if for all_icons_show -->
      </section><!-- /footer-bottom-middle -->
     ";
        }
        // line 46
        echo " <!-- end condition if copyright or social icons -->
     ";
        // line 47
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_bottom", [], "any", false, false, true, 47)) {
            // line 48
            echo "       <div class=\"footer-bottom\">
         ";
            // line 49
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_bottom", [], "any", false, false, true, 49), 49, $this->source), "html", null, true);
            echo "
       </div> <!--/.footer-bottom -->
     ";
        }
        // line 51
        echo " <!-- end condition for footer_bottom -->
    </div><!-- /.container -->
  </div> 
  <div class=\"text-center\" style=\"background: #000; font-size:14px; padding: 10px;\"> &copy; ";
        // line 54
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, "now", "Y"), "html", null, true);
        echo " ";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["site_name"] ?? null), 54, $this->source), "html", null, true);
        echo ", All rights reserved.</div>
  <!--/.footer -->
</footer>
";
        // line 57
        if (($context["scrolltotop_on"] ?? null)) {
            // line 58
            echo "<div class=\"scrolltop\"><i class=\"fa fa-angle-up\" aria-hidden=\"true\"></i></div>
";
        }
        // line 60
        echo "<!-- End: Footer -->
";
        // line 61
        if (($context["bootstrapicons"] ?? null)) {
            // line 62
            echo "  ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->attachLibrary("tara/bootstrap-icons"), "html", null, true);
            echo "
";
        }
    }

    public function getTemplateName()
    {
        return "@tara/template-parts/footer.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  188 => 62,  186 => 61,  183 => 60,  179 => 58,  177 => 57,  169 => 54,  164 => 51,  158 => 49,  155 => 48,  153 => 47,  150 => 46,  145 => 44,  141 => 43,  139 => 42,  136 => 41,  134 => 40,  129 => 37,  126 => 36,  124 => 35,  119 => 33,  113 => 31,  110 => 30,  108 => 29,  105 => 28,  99 => 26,  96 => 25,  94 => 24,  91 => 23,  85 => 21,  82 => 20,  80 => 19,  77 => 18,  70 => 15,  67 => 14,  65 => 13,  61 => 11,  59 => 10,  56 => 9,  50 => 7,  47 => 6,  45 => 5,  39 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "@tara/template-parts/footer.html.twig", "C:\\wamp64\\www\\ncm\\update\\Re-desigen\\web\\themes\\tara\\templates\\template-parts\\footer.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("if" => 5, "include" => 42);
        static $filters = array("escape" => 7, "date" => 54);
        static $functions = array("attach_library" => 62);

        try {
            $this->sandbox->checkSecurity(
                ['if', 'include'],
                ['escape', 'date'],
                ['attach_library']
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
