git log | head -4 | awk '
  BEGIN {
    print "<?php";
  }
  /^Author/{
      match($0,"(^Author):[ ]*([A-Za-z_][A-Za-z \\._]*?)[ ]*<(.*)>", parts);
      printf "$config_git_author=\"%s\";\n",parts[2]
      printf "$config_git_email=\"%s\";\n",parts[3]
  }
  /^Date/ {
     match($0, "(Date:)[ ]*([A-Za-z 0-9:]*)", parts);
      printf "$config_git_date=\"%s\";\n",parts[2]

  }
  END{
    print "?>";
  } ' > config/config.inc.php
