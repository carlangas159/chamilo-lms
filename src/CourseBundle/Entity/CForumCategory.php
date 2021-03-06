<?php

declare(strict_types=1);

/* For licensing terms, see /license.txt */

namespace Chamilo\CourseBundle\Entity;

use Chamilo\CoreBundle\Entity\AbstractResource;
use Chamilo\CoreBundle\Entity\ResourceInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CForumCategory.
 *
 * @ORM\Table(
 *     name="c_forum_category",
 *     indexes={
 *     }
 * )
 * @ORM\Entity
 */
class CForumCategory extends AbstractResource implements ResourceInterface
{
    /**
     * @ORM\Column(name="iid", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected int $iid;

    /**
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="cat_title", type="string", length=255, nullable=false)
     */
    protected string $catTitle;

    /**
     * @ORM\Column(name="cat_comment", type="text", nullable=true)
     */
    protected ?string $catComment;

    /**
     * @ORM\Column(name="cat_order", type="integer", nullable=false)
     */
    protected int $catOrder;

    /**
     * @ORM\Column(name="locked", type="integer", nullable=false)
     */
    protected int $locked;

    /**
     * @var Collection|CForumForum[]
     *
     * @ORM\OneToMany(targetEntity="Chamilo\CourseBundle\Entity\CForumForum", mappedBy="forumCategory")
     */
    protected Collection $forums;

    public function __construct()
    {
        $this->catComment = '';
        $this->locked = 0;
        $this->forums = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getCatTitle();
    }

    /**
     * Get iid.
     *
     * @return int
     */
    public function getIid()
    {
        return $this->iid;
    }

    /**
     * Set catTitle.
     *
     * @return CForumCategory
     */
    public function setCatTitle(string $catTitle)
    {
        $this->catTitle = $catTitle;

        return $this;
    }

    /**
     * Get catTitle.
     *
     * @return string
     */
    public function getCatTitle()
    {
        return $this->catTitle;
    }

    public function setCatComment(string $catComment): self
    {
        $this->catComment = $catComment;

        return $this;
    }

    public function getCatComment(): ?string
    {
        return $this->catComment;
    }

    /**
     * Set catOrder.
     *
     * @return CForumCategory
     */
    public function setCatOrder(int $catOrder)
    {
        $this->catOrder = $catOrder;

        return $this;
    }

    /**
     * Get catOrder.
     *
     * @return int
     */
    public function getCatOrder()
    {
        return $this->catOrder;
    }

    /**
     * Set locked.
     *
     * @return CForumCategory
     */
    public function setLocked(int $locked)
    {
        $this->locked = $locked;

        return $this;
    }

    /**
     * Get locked.
     *
     * @return int
     */
    public function getLocked()
    {
        return $this->locked;
    }

    /**
     * Get forums.
     *
     * @return Collection|CForumForum[]
     */
    public function getForums()
    {
        return $this->forums;
    }

    public function getResourceIdentifier(): int
    {
        return $this->getIid();
    }

    public function getResourceName(): string
    {
        return $this->getCatTitle();
    }

    public function setResourceName(string $name): self
    {
        return $this->setCatTitle($name);
    }
}
