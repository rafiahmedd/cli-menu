<?php

namespace PhpSchool\CliMenu\Dialogue;

use PhpSchool\Terminal\InputCharacter;
use PhpSchool\Terminal\NonCanonicalReader;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class Confirm extends Dialogue
{

    /**
     * Display confirmation with a button with the given text
     */
    public function display(string $confirmText = 'OK') : void
    {
        $this->assertMenuOpen();

        $this->terminal->moveCursorToRow($this->y);

        $promptWidth = mb_strlen($this->text) + 4;

        $this->emptyRow();

        $this->write(sprintf(
            "%s%s%s%s%s\n",
            $this->style->getUnselectedSetCode(),
            str_repeat(' ', $this->style->getPadding()),
            $this->text,
            str_repeat(' ', $this->style->getPadding()),
            $this->style->getUnselectedUnsetCode()
        ));

        $this->emptyRow();

        $confirmText = sprintf(' < %s > ', $confirmText);
        $leftFill    = ($promptWidth / 2) - (mb_strlen($confirmText) / 2);

        $this->write(sprintf(
            "%s%s%s%s%s%s%s%s%s\n",
            $this->style->getUnselectedSetCode(),
            str_repeat(' ', $leftFill),
            $this->style->getUnselectedUnsetCode(),
            $this->style->getSelectedSetCode(),
            $confirmText,
            $this->style->getSelectedUnsetCode(),
            $this->style->getUnselectedSetCode(),
            str_repeat(' ', ceil($promptWidth - $leftFill - mb_strlen($confirmText))),
            $this->style->getUnselectedUnsetCode()
        ));

        $this->write(sprintf(
            "%s%s%s%s%s\n",
            $this->style->getUnselectedSetCode(),
            str_repeat(' ', $this->style->getPadding()),
            str_repeat(' ', mb_strlen($this->text)),
            str_repeat(' ', $this->style->getPadding()),
            $this->style->getUnselectedUnsetCode()
        ));

        $this->terminal->moveCursorToTop();

        $reader = new NonCanonicalReader($this->terminal);

        while ($char = $reader->readCharacter()) {
            if ($char->isControl() && $char->getControl() === InputCharacter::ENTER) {
                $this->parentMenu->redraw();
                return;
            }
        }
    }
}
