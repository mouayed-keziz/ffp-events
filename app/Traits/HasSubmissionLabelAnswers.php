<?php

namespace App\Traits;

use App\Enums\FormField;

trait HasSubmissionLabelAnswers
{
    /**
     * Get all label-answer pairs from visitor submission answers
     * 
     * @param string $language Language code (default: 'fr')
     * @return array Array of label-answer pairs
     */
    public function getFormattedAnswersAttribute(string $language = 'fr'): array
    {
        $formattedAnswers = [];

        if (empty($this->answers)) {
            return $formattedAnswers;
        }

        // For visitor submissions, the structure is sections with fields directly
        if ($this instanceof \App\Models\VisitorSubmission) {
            foreach ($this->answers as $section) {
                if (isset($section['fields'])) {
                    foreach ($section['fields'] as $field) {
                        $fieldType = FormField::tryFrom($field['type']);
                        if ($fieldType) {
                            $labelAnswer = $fieldType->getLabelAnswerPair($field, $language);
                            if (!empty($labelAnswer['answer'])) {
                                $formattedAnswers[] = $labelAnswer;
                            }
                        }
                    }
                }
            }
        }

        // For exhibitor submissions, we need to handle both answers and post_answers
        if ($this instanceof \App\Models\ExhibitorSubmission) {
            // Process main answers (skip the last element which is pricing)
            $answersToProcess = $this->answers;
            if (is_array($answersToProcess) && count($answersToProcess) > 1) {
                // Remove the last element if it looks like pricing data
                $lastElement = end($answersToProcess);
                if (is_array($lastElement) && isset($lastElement['DZD'])) {
                    array_pop($answersToProcess);
                }
            }

            foreach ($answersToProcess as $section) {
                if (isset($section['sections'])) {
                    foreach ($section['sections'] as $subsection) {
                        if (isset($subsection['fields'])) {
                            foreach ($subsection['fields'] as $field) {
                                $fieldType = FormField::tryFrom($field['type']);
                                if ($fieldType) {
                                    $labelAnswer = $fieldType->getLabelAnswerPair($field, $language);
                                    if (!empty($labelAnswer['answer'])) {
                                        $formattedAnswers[] = $labelAnswer;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            // Process post answers if available
            if (!empty($this->post_answers)) {
                $postAnswersToProcess = $this->post_answers;
                if (is_array($postAnswersToProcess) && count($postAnswersToProcess) > 1) {
                    // Remove the last element if it looks like pricing data
                    $lastElement = end($postAnswersToProcess);
                    if (is_array($lastElement) && isset($lastElement['DZD'])) {
                        array_pop($postAnswersToProcess);
                    }
                }

                foreach ($postAnswersToProcess as $section) {
                    if (isset($section['sections'])) {
                        foreach ($section['sections'] as $subsection) {
                            if (isset($subsection['fields'])) {
                                foreach ($subsection['fields'] as $field) {
                                    $fieldType = FormField::tryFrom($field['type']);
                                    if ($fieldType) {
                                        $labelAnswer = $fieldType->getLabelAnswerPair($field, $language);
                                        if (!empty($labelAnswer['answer'])) {
                                            $formattedAnswers[] = $labelAnswer;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $formattedAnswers;
    }

    /**
     * Get formatted answers as a JSON string
     * 
     * @param string $language Language code (default: 'fr')
     * @return string JSON string of formatted answers
     */
    public function getFormattedAnswersJsonAttribute(string $language = 'fr'): string
    {
        return json_encode($this->getFormattedAnswersAttribute($language), JSON_UNESCAPED_UNICODE);
    }
}
