<tr>
  <td align="center" class="kmButtonBarInner" style="padding-bottom:10px;padding-left:10px;padding-right:10px;" valign="top">
    <table border="0" cellpadding="0" cellspacing="0" class="kmButtonBarContentContainer" width="100%">
      <tbody>
      <tr>
        <td align="center" style="padding-left:9px;padding-right:9px;">
          <table border="0" cellpadding="0" cellspacing="0" class="kmButtonBarContent">
            <tbody>
            <tr>
              <td align="center" valign="top">
                <table border="0" cellpadding="0" cellspacing="0">
                  <tbody>
                  <tr>
                    <td valign="top">
                      <?php

                      // check if the repeater field has rows of data
                      if( have_rows('socials_icons', 'options') ):

                        // loop through the rows of data
                        while ( have_rows('socials_icons', 'options') ) : the_row();
                          $image = get_sub_field('icone');
                          ?>
                        <table align="left" border="0" cellpadding="0" cellspacing="0" class="">
                          <tbody>
                          <tr>
                            <td align="center" style="padding-right:10px;" valign="top">
                              <a rel="noreferrer noopener" href="<?php the_sub_field('url'); ?>" target="_blank">
                                <img style="max-width: 50%" src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt'] ?>" />
                              </a>
                            </td>
                          </tr>
                          </tbody>
                        </table>
                          <?php

                        endwhile;

                      else :

                        // no rows found

                      endif;

                      ?>

                    </td>
                  </tr>
                  </tbody>
                </table>
              </td>
            </tr>
            </tbody>
          </table>
        </td>
      </tr>
      </tbody>
    </table>
  </td>
</tr>
