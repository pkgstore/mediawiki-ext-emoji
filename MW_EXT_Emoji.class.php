<?php

namespace MediaWiki\Extension\PkgStore;

use ConfigException;
use MWException;
use OutputPage, Parser, Skin;

/**
 * Class MW_EXT_Emoji
 */
class MW_EXT_Emoji
{
  /**
   * Get emoji file.
   *
   * @param $id
   *
   * @return string
   * @throws ConfigException
   */
  private static function getEmoji($id): string
  {
    $path = MW_EXT_Kernel::getConfig('ScriptPath') . '/vendor/metastore/lib-emoji/resources/assets/images/';
    $id = MW_EXT_Kernel::outNormalize($id);
    return $path . $id . '.svg';
  }

  /**
   * Register tag function.
   *
   * @param Parser $parser
   *
   * @return void
   * @throws MWException
   */
  public static function onParserFirstCallInit(Parser $parser): void
  {
    $parser->setFunctionHook('emoji', [__CLASS__, 'onRenderTag']);
  }

  /**
   * Render tag function.
   *
   * @param Parser $parser
   * @param string $id
   * @param string $size
   *
   * @return string
   * @throws ConfigException
   */
  public static function onRenderTag(Parser $parser, string $id = '', string $size = ''): string
  {
    // Argument: id.
    $getID = MW_EXT_Kernel::outClear($id ?? '' ?: '');
    $outID = self::getEmoji($getID);

    // Argument: size.
    $getSize = MW_EXT_Kernel::outClear($size ?? '' ?: '');
    $outSize = empty($getSize) ? '' : ' width: ' . $getSize . 'em; height: ' . $getSize . 'em;';

    // Out HTML.
    $outHTML = '<span style="background-image: url(' . $outID . ');' . $outSize . '" class="mw-emoji navigation-not-searchable"></span>';

    // Out parser.
    return $parser->insertStripItem($outHTML, $parser->getStripState());
  }

  /**
   * Load resource function.
   *
   * @param OutputPage $out
   * @param Skin $skin
   *
   * @return void
   */
  public static function onBeforePageDisplay(OutputPage $out, Skin $skin): void
  {
    $out->addModuleStyles(['ext.mw.emoji.styles']);
  }
}
