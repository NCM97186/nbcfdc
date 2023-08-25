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

/* @tara/template-parts/social-icons2.html.twig */
class __TwigTemplate_b957b2c14248a5ccb5326134ce6e67353ac7fb48256ad8dd34d0ea44aeb93791 extends \Twig\Template
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
        echo "<ul class=\"social-icons\">
<i style=\"font-size:18px\" class=\"fa fa-level-down\"></i> <li>
<span style=\"font-size:16px;\">Skip To Main Content<span></li>
<i class=\"fa fa-eye-slash rounded-6 bg-light text-dark p-1\" style=\"font-size:18px;border-radius: 50px;
    height: 28px;
    width: 28px;\"></i>
<i class=\"fa fa-sitemap bg-light text-dark p-1\" style=\"font-size:16px;border-radius: 50px;
    height: 28px;
    width: 28px;\"></i>


<img src=\"https://socialjustice.gov.in/public/latest/images/black-white.png\" style=\"height:28px;\">
  <a class=\"btn btn-light dropdown-toggle\" href=\"#\" role=\"button\" id=\"dropdownMenuLink\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\" style=\"border-radius: 50px;
    height: 28px;
    width: 65px; padding: 0px; color:#090808;\">
  <img src=\"https://socialjustice.gov.in/public/latest/images/social-group.png\">
  </a>
    <a class=\"btn btn-light dropdown-toggle\" href=\"#\" role=\"button\" id=\"dropdownMenuLink\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\" style=\"border-radius: 50px;
    height: 28px;
    width: 65px; padding: 0px; color:#090808;\">
    TT
  </a>

  
<button type=\"button\" class=\"btn btn-light btn-sm\" style=\"border-radius: 50px;
    height: 28px;
    width: 50px;padding-top:3px;\">हिंदी</button>
  <form class=\"form-search\" method=\"get\" id=\"s\" action=\"http://125.20.102.83/ncm/Re-desigen/web/\">
    <div class=\"input-append d-flex\">
        <input type=\"text\" class=\"input-medium search-query\" name=\"s\" placeholder=\"Search\" value=\"\" style=\"height: 28px;\">
        <button type=\"submit\" class=\"add-on\" style=\"background-color: white;padding:0 8px;border-radius: 4px;\"><i class=\"fa fa-search fa-lg bg-light text-dark p-1\" aria-hidden=\"true\" style=\"background-color: aliceblue;
    height: 20px; padding-top: 5px;display: contents\"></i></button>
    </div>
</form>

 
 </li>
</ul>
";
    }

    public function getTemplateName()
    {
        return "@tara/template-parts/social-icons2.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  39 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "@tara/template-parts/social-icons2.html.twig", "C:\\wamp64\\www\\ncm\\update\\Re-desigen\\web\\themes\\tara\\templates\\template-parts\\social-icons2.html.twig");
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
