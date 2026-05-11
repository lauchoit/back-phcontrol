<?php

namespace Lauchoit\LaravelHexMod\SendNotification\Domain\Factory;

use Lauchoit\LaravelHexMod\SendNotification\Domain\Entity\NotificationBuilder;

class EmailMessageBuilder extends NotificationBuilder
{
    private ?string $to = null;

    private ?string $subject = null;

    private ?string $html = null;

    private array $cc = [];

    private array $bcc = [];

    private array $attachments = [];

    private ?string $replyTo = null;

    private array $data = [];

    private ?array $requiredVariables = null;

    public static function create(): self
    {
        return new self;
    }

    public function to($email): self
    {
        $this->to = $email;

        return $this;
    }

    public function subject($subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function html($html): self
    {
        $this->html = $html;

        return $this;
    }

    public function cc(array $cc): self
    {
        $this->cc = $cc;

        return $this;
    }

    public function bcc(array $bcc): self
    {
        $this->bcc = $bcc;

        return $this;
    }

    public function attachments(array $attachments): self
    {
        $this->attachments = $attachments;

        return $this;
    }

    public function replyTo(?string $email): self
    {
        $this->replyTo = $email;

        return $this;
    }

    public function withRequiredVariables(array|string $variables): self
    {
        if (is_string($variables)) {
            $variables = json_decode($variables, true);
        }
        $this->requiredVariables = $variables;

        return $this;
    }

    public function with(array|object $data): self
    {
        if (is_object($data)) {
            $this->data = array_merge($this->data, $this->extractDataFromObject($data));
        } else {
            $this->data = array_merge($this->data, $data);
        }

        return $this;
    }

    private function extractDataFromObject(object $object): array
    {
        $data = [];

        if ($this->requiredVariables !== null) {
            foreach (array_keys($this->requiredVariables) as $variable) {
                $value = $this->getPropertyValue($object, $variable);
                if ($value !== null) {
                    $data[$variable] = $value;
                }
            }
        } else {
            $data = $this->getAllProperties($object);
        }

        return $data;
    }

    private function getPropertyValue(object $object, $property): mixed
    {
        $getter = 'get'.ucfirst($property);
        if (method_exists($object, $getter)) {
            return $object->$getter();
        }

        $snakeGetter = 'get_'.$this->camelToSnake($property);
        if (method_exists($object, $snakeGetter)) {
            return $object->$snakeGetter();
        }

        if (property_exists($object, $property)) {
            return $object->$property;
        }

        $snakeProperty = $this->camelToSnake($property);
        if (property_exists($object, $snakeProperty)) {
            return $object->$snakeProperty;
        }

        return null;
    }

    private function getAllProperties(object $object): array
    {
        $data = [];
        $reflection = new \ReflectionClass($object);

        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            $methodName = $method->getName();

            // Detectar getters
            if (str_starts_with($methodName, 'get') && $method->getNumberOfParameters() === 0) {
                $property = lcfirst(substr($methodName, 3));
                $data[$property] = $object->$methodName();
            }
        }

        return $data;
    }

    private function camelToSnake($input): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }

    public function build(): self
    {
        if (! $this->to || ! $this->subject || ! $this->html) {
            throw new \LogicException('EmailMessageBuilder: to/subject/html are required.');
        }

        if ($this->requiredVariables !== null) {
            $this->validateRequiredVariables();
        }

        $this->html = $this->replaceVariables($this->html, $this->data);
        $this->subject = $this->replaceVariables($this->subject, $this->data);

        return $this;
    }

    private function validateRequiredVariables(): void
    {
        $missingVariables = [];

        foreach ($this->requiredVariables as $variable => $isRequired) {
            if ($isRequired && ! array_key_exists($variable, $this->data)) {
                $missingVariables[] = $variable;
            }
        }

        if (! empty($missingVariables)) {
            throw new \LogicException(
                'EmailMessageBuilder: Missing required variables: '.implode(', ', $missingVariables)
            );
        }
    }

    private function replaceVariables($template, array $data): string
    {
        foreach ($data as $key => $value) {
            $template = str_replace('{{'.$key.'}}', $value, $template);
        }

        return $template;
    }

    public function getTo(): ?string
    {
        return $this->to;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function getHtml(): ?string
    {
        return $this->html;
    }

    public function getCc(): array
    {
        return $this->cc;
    }

    public function getBcc(): array
    {
        return $this->bcc;
    }

    public function getAttachments(): array
    {
        return $this->attachments;
    }

    public function getReplyTo(): ?string
    {
        return $this->replyTo;
    }
}
