<?php


namespace tderenko\pagination;


class Pagination
{
    protected int $total = 0;
    protected int $limit = 0;
    protected int $currentPage = 1;
    protected int $maxPages = 1;
    protected int $endSize = 1;
    protected int $midSize = 2;
    protected bool $prevNext = true;
    protected string $prevText = 'Prev';
    protected string $nextText = 'Next';

    public function __construct(array $attr = [])
    {
        foreach ($attr as $property => $value) {
            $method = 'set' . ucfirst($property);
            if (method_exists($this, $method))
                $this->$method($value);
        }
    }

    /**
     * @param array $attr
     * @return $this
     */
    public function init(array $attr = []): self
    {
        return new self($attr);
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;
        return $this;
    }

    public function setLimit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function setCurrentPage(int $currentPage): self
    {
        $this->currentPage = $currentPage;
        return $this;
    }

    public function setMidSize(int $size): self
    {
        $this->midSize = $size;
        return $this;
    }

    public function setEndSize(int $size): self
    {
        $this->midSize = $size;
        return $this;
    }

    public function setPrevNext(bool $prevNext): self
    {
        $this->prevNext = $prevNext;
        return $this;
    }

    public function setPrevText(string $text): self
    {
        $this->prevText = $text;
        return $this;
    }

    public function setNextText(string $text): self
    {
        $this->nextText = $text;
        return $this;
    }

    public function render()
    {
        return $this->pagination();
    }

    protected function pagination()
    {
        if(!$this->total)
            return [];

        $this->maxPages = ceil($this->total / $this->limit);

        if ($this->maxPages < 2)
            return [];

        if ($this->currentPage < 1)
            $this->currentPage = 1;

        $pages = [];
        $dots = false;

        for ($n = 1; $n <= $this->maxPages; $n++) {
            if ($n == $this->currentPage) {
                $pages[] = [
                    'label' => $n,
                    'page' => $n,
                    'class' => 'current'
                ];
                $dots = true;
            } else {
                if ($n <= $this->endSize ||
                        ($this->currentPage && $n >= $this->currentPage - $this->midSize && $n <= $this->currentPage + $this->midSize) ||
                        $n > $this->maxPages - $this->endSize) {
                    $pages[] = [
                        'label' => $n,
                        'page' => $n,
                        'class' => ''
                    ];
                    $dots = true;
                } elseif ($dots) {
                    $pages[] = [
                        'label' => '...',
                        'page' => false,
                        'class' => 'dots'
                    ];
                    $dots = false;
                }
            }
        }

        if ($this->prevNext && $this->currentPage > 1) {
            array_unshift($pages, [
                'label' => $this->prevText,
                'page' => ($this->currentPage - 1),
                'class' => 'prev'
            ]);
        }

        if ($this->prevNext && $this->currentPage < $this->maxPages){
            array_push($pages, [
                'label' => $this->nextText,
                'page' => ($this->currentPage + 1),
                'class' => 'next'
            ]);
        }

        return [
            'pages' => $pages,
            'offset' => $this->limit * ($this->currentPage - 1),
            'limit' => $this->limit,
            'total' => $this->total
        ];
    }
}