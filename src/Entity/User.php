<?php

namespace App\Entity;

use App\Enum\Group\UserGroups;
use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User extends AbstractEntity implements UserInterface, PasswordAuthenticatedUserInterface
{

    #[ORM\Column(length: 180, unique: true)]
    #[Constraints\Type(type: Types::STRING)]
    #[Constraints\Length(min: 5, max: 180)]
    #[Constraints\NotBlank(allowNull: false, groups: [UserGroups::CREATE])]
    #[Constraints\Email]
    #[Groups([
        UserGroups::CREATE,
        UserGroups::SHOW,
        UserGroups::UPDATE
    ])]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string|null The hashed password
     */
    #[ORM\Column]
    #[Constraints\Type(type: Types::STRING)]
    #[Constraints\Length(min:8, max: 255)]
    #[Constraints\NotBlank(allowNull: false, groups: [UserGroups::CREATE])]
    #[Groups([
        UserGroups::CREATE,
        UserGroups::UPDATE,
    ])]
    private ?string $password = null;

    #[ORM\Column(length: 180)]
    #[Constraints\Type(type: Types::STRING)]
    #[Constraints\Length(min: 1, max: 180)]
    #[Constraints\NotBlank(allowNull: false)]
    #[Groups([
        UserGroups::CREATE,
        UserGroups::SHOW,
        UserGroups::UPDATE,
    ])]
    private ?string $firstName = null;

    #[ORM\Column(length: 180)]
    #[Constraints\Type(type: Types::STRING)]
    #[Constraints\Length(min: 1, max: 180)]
    #[Constraints\NotBlank(allowNull: false)]
    #[Groups([
        UserGroups::CREATE,
        UserGroups::SHOW,
        UserGroups::UPDATE,
    ])]
    private ?string $lastName = null;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }
}
