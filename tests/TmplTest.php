<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PhpPerlTemplate\Template;

final class TmplTest extends TestCase
{
  private $tmpl;

  private $opts = [
    "filename" => "basic.tmpl", # required, filename only without path
    "search_path_on_include" => 1, # used in conjunction with "paths" option
    "paths" => [__DIR__], 
    "die_on_bad_params" => 0,
    "debug" => 0, # optional
  ];

  public function testCanOutputTemplate(): void
  {
    $this->tmpl = new Template($this->opts);

    $this->tmpl->AddParam('CASEREFERENCE', 'dvorak');
    $this->tmpl->AddParam('_SP_10', '          ');
    $this->tmpl->AddParam('_SP_20', '                    ');

    $this->assertEquals(
      "<!---- Chapter 2: {partyAfirstname}'s Information ---->\n<b>dvorak</b><u>          </u>\n\n\n<b>dvorak</b>\n\n\n<u>                    </u>",
      $this->tmpl->output()
    );

    ob_start();
    $this->tmpl->ListNodes();
    $listNodes = ob_get_contents();
    ob_end_clean();

    $this->assertEquals(
<<<NODES
<b>Contents of linearized parse tree</b><br><b>[0]</b> - MARKUP - <code>&lt;!---- Chapter 2: {partyAfirstname}'s Information ----&gt;\\n&lt;b&gt;</code><br>
<b>[1]</b> - VAR - <code>casereference</code><br>
<b>[2]</b> - MARKUP - <code>&lt;/b&gt;&lt;u&gt;</code><br>
<b>[3]</b> - VAR - <code>_sp_10</code><br>
<b>[4]</b> - MARKUP - <code>&lt;/u&gt;\\n\\n\\n&lt;b&gt;</code><br>
<b>[5]</b> - VAR - <code>casereference</code><br>
<b>[6]</b> - MARKUP - <code>&lt;/b&gt;\\n\\n\\n&lt;u&gt;</code><br>
<b>[7]</b> - VAR - <code>_sp_20</code><br>
<b>[8]</b> - MARKUP - <code>&lt;/u&gt;</code><br>
<b>Variables used in template</b><br><pre>Array
(
    [casereference] => Array
        (
            [0] => 2
            [1] => 5
        )

    [_sp_10] => Array
        (
            [0] => 2
        )

    [_sp_20] => Array
        (
            [0] => 8
        )

)
</pre>
NODES,
      $listNodes
    );
  }
}

