<?php

$GLOBALS['TL_DCA']['tl_tm_cookie'] = array
(

    // Config
    'config'      => array
    (
        'dataContainer' => 'Table',
        'onload_callback' => array
        (
            array(\Slashworks\ContaoTrackingManagerBundle\DataContainer\Cookies::class, 'onloadCallback'),
        ),
        'sql'           => array
        (
            'keys' => array
            (
                'id' => 'primary',
            ),
        ),
    ),

    // List
    'list'        => array
    (
        'sorting'           => array
        (
            'mode'            => 1,
            'fields'          => array('name'),
            'icon'            => 'pagemounts.svg',
            'panelLayout'     => 'filter;search,sort',
            'disableGrouping' => true,
        ),
        'label'             => array
        (
            'fields'      => array('name'),
            'showColumns' => true,
        ),
        'global_operations' => array
        (
            'createBase' => array
            (
                'href'  => 'key=createBase',
                'class' => 'header_theme_import',
            ),
        ),
        'operations'        => array
        (
            'edit'   => array
            (
                'href' => 'act=edit',
                'icon' => 'edit.svg',
            ),
            'delete' => array
            (
                'href'       => 'act=delete',
                'icon'       => 'delete.svg',
                'attributes' => 'onclick="if(!confirm(\'' . ($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? null) . '\'))return false;Backend.getScrollOffset()"',

            ),
            'toggle' => array
            (
                'icon'            => 'visible.svg',
                'attributes'      => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                'button_callback' => array('tl_tm_cookie', 'toggleIcon'),
                'showInHeader'    => true,
            ),
            'show'   => array
            (
                'href' => 'act=show',
                'icon' => 'show.svg',
            ),
        ),
    ),

    // Palettes
    'palettes'    => array
    (
        'default' => '{title_legend},name,label,descriptions,isBaseCookie,published',
    ),

    // Subpalettes
    'subpalettes' => array
    (),

    // Fields
    'fields'      => array
    (
        'id'           => array
        (
            'label' => array('ID'),
            'sql'   => "int(10) unsigned NOT NULL auto_increment",
        ),
        'tstamp'       => array
        (
            'label'   => &$GLOBALS['TL_LANG']['tl_tm_cookie']['tstamp'],
            'sorting' => true,
            'sql'     => "int(10) unsigned NOT NULL default '0'",
        ),
        'pid'          => array
        (
            'label' => &$GLOBALS['TL_LANG']['tl_tm_cookie']['pid'],
            'sql'   => "varchar(255) NOT NULL default ''",
        ),
        'name'         => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_tm_cookie']['name'],
            'exclude'   => true,
            'search'    => true,
            'sorting'   => true,
            'inputType' => 'text',
            'eval'      => array('tl_class' => 'w50'),
            'sql'       => "varchar(255) NOT NULL default ''",
        ),
        'label'        => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_tm_cookie']['label'],
            'exclude'   => true,
            'search'    => true,
            'sorting'   => true,
            'inputType' => 'text',
            'eval'      => array('tl_class' => 'w50'),
            'sql'       => "varchar(255) NOT NULL default ''",
        ),
        'isBaseCookie' => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_tm_cookie']['isBaseCookie'],
            'exclude'   => true,
            'search'    => true,
            'sorting'   => true,
            'inputType' => 'checkbox',
            'eval'      => array('submitOnChange' => true, 'tl_class' => 'w50'),
            'sql'       => "varchar(255) NOT NULL default ''",
        ),
        'published'    => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_tm_cookie']['published'],
            'exclude'   => true,
            'search'    => true,
            'sorting'   => true,
            'inputType' => 'checkbox',
            'eval'      => array('tl_class' => 'w50'),
            'sql'       => "varchar(255) NOT NULL default ''",
        ),
        'descriptions' => array
        (
            'label'       => &$GLOBALS['TL_LANG']['tl_tm_cookie']['descriptions'],
            'exclude'     => true,
            'inputType'   => 'multiColumnWizard',
            'dragAndDrop' => true,
            'eval'        => [
                // add this line for hide one or all buttons

                'columnFields' => [
                    'label'       => [
                        'label'     => &$GLOBALS['TL_LANG']['tl_tm_cookie']['descriptionLabel'],
                        'exclude'   => true,
                        'inputType' => 'text',
                    ],
                    'description' => [
                        'label'     => &$GLOBALS['TL_LANG']['tl_tm_cookie']['description'],
                        'exclude'   => true,
                        'inputType' => 'text',
                        'eval'      => ['preserveTags' => true, 'allowHtml' => true],
                    ],
                ],
                'tl_class'     => 'm12 clr',
            ],
            'sql'         => 'blob NULL',
        ),
        'templates'    => array
        (
            'inputType'        => 'select',
            'options_callback' => array(
                \Slashworks\ContaoTrackingManagerBundle\DataContainer\Cookies::class,
                'getTemplates',
            ),
            'eval'             => array('multiple' => true, 'chosen' => true),
            'sql'              => "blob NULL",
        ),
    ),
);


class tl_tm_cookie extends \Contao\Backend
{

    /**
     * Return the "toggle visibility" button
     *
     * @param array  $row
     * @param string $href
     * @param string $label
     * @param string $title
     * @param string $icon
     * @param string $attributes
     *
     * @return string
     */
    public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
    {
        if (Contao\Input::get('tid')) {
            $this->toggleVisibility(Contao\Input::get('tid'), (Contao\Input::get('state') == 1),
                (@func_get_arg(12) ?: null));
            $this->redirect($this->getReferer());
        }


        $href .= '&amp;tid=' . $row['id'] . '&amp;state=' . ($row['published'] ? '' : 1);

        if (!$row['published']) {
            $icon = 'invisible.svg';
        }

        return '<a href="' . $this->addToUrl($href) . '" title="' . Contao\StringUtil::specialchars($title) . '"' . $attributes . '>' . Contao\Image::getHtml($icon,
                $label, 'data-state="' . ($row['published'] ? 1 : 0) . '"') . '</a> ';
    }


    /**
     * Disable/enable a user group
     *
     * @param integer              $intId
     * @param boolean              $blnVisible
     * @param Contao\DataContainer $dc
     *
     * @throws Contao\CoreBundle\Exception\AccessDeniedException
     */
    public function toggleVisibility($intId, $blnVisible, Contao\DataContainer $dc = null)
    {
        // Set the ID and action
        Contao\Input::setGet('id', $intId);
        Contao\Input::setGet('act', 'toggle');

        if ($dc) {
            $dc->id = $intId; // see #8043
        }

        // Set the current record
        if ($dc) {
            $objRow = $this->Database->prepare("SELECT * FROM tl_tm_cookie WHERE id=?")
                                     ->limit(1)
                                     ->execute($intId);

            if ($objRow->numRows) {
                $dc->activeRecord = $objRow;
            }
        }

        $time = time();

        // Update the database
        $this->Database->prepare("UPDATE tl_tm_cookie SET tstamp=$time, published='" . ($blnVisible ? '1' : '') . "' WHERE id=?")
                       ->execute($intId);

        if ($dc) {
            $dc->activeRecord->tstamp = $time;
            $dc->activeRecord->published = ($blnVisible ? '1' : '');
        }

    }
}
