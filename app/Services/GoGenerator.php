<?php

namespace App\Services;

use Illuminate\Support\Str;

class GoGenerator
{
    protected array $schema;
    protected string $resourceName;
    protected string $modelName;
    protected string $tableName;

    public function __construct(array $schema)
    {
        $this->schema = $schema;
        $this->resourceName = $schema['resource_name'] ?? 'Resource';
        $this->modelName = Str::studly(Str::singular($this->resourceName));
        $this->tableName = Str::snake(Str::plural($this->resourceName));
    }

    public function generate(): array
    {
        return [
            'Model' => $this->generateModel(),
            'Repository' => $this->generateRepository(),
            'Handler' => $this->generateHandler(),
            'Routes' => $this->generateRoutes(),
        ];
    }

    protected function mapType(string $type): string
    {
        return match ($type) {
            'integer' => 'int',
            'boolean' => 'bool',
            'uuid'    => 'uuid.UUID',
            'date'    => 'time.Time',
            default   => 'string',
        };
    }

    protected function generateModel(): string
    {
        $fields = "";
        $hasUuid = false;
        $hasTime = false;

        foreach ($this->schema['fields'] as $field) {
            $name = Str::studly($field['name']);
            $jsonName = Str::camel($field['name']);
            $type = $field['type'] ?? 'string';
            $goType = $this->mapType($type);
            
            if ($goType === 'uuid.UUID') $hasUuid = true;
            if ($goType === 'time.Time') $hasTime = true;

            $gormTag = "";
            if ($field['name'] === 'id') {
                $gormTag = "gorm:\"primaryKey;type:uuid;default:uuid_generate_v4()\" ";
            } else if (!($field['is_nullable'] ?? false)) {
                $gormTag = "gorm:\"not null\" ";
            }

            $fields .= "    {$name} {$goType} `{$gormTag}json:\"{$jsonName}\"`\n";
        }

        $imports = [];
        if ($hasUuid) $imports[] = '"github.com/google/uuid"';
        if ($hasTime) $imports[] = '"time"';
        
        $importsString = "";
        if (!empty($imports)) {
            $importsString = "import (\n    " . implode("\n    ", $imports) . "\n)\n";
        }

        return <<<GO
package models

{$importsString}
type {$this->modelName} struct {
{$fields}
    CreatedAt time.Time `json:"createdAt"`
    UpdatedAt time.Time `json:"updatedAt"`
}
GO;
    }

    protected function generateRepository(): string
    {
        return <<<GO
package repository

import (
	"context"
	"errors"
	"gorm.io/gorm"
	"your_project/models"
)

type {$this->modelName}Repository interface {
	FindAll(ctx context.Context) ([]models.{$this->modelName}, error)
	FindByID(ctx context.Context, id string) (models.{$this->modelName}, error)
	Create(ctx context.Context, data *models.{$this->modelName}) error
	Update(ctx context.Context, data *models.{$this->modelName}) error
	Delete(ctx context.Context, id string) error
}

type {$this->modelName}RepositoryImpl struct {
	db *gorm.DB
}

func New{$this->modelName}Repository(db *gorm.DB) {$this->modelName}Repository {
	return &{$this->modelName}RepositoryImpl{db: db}
}

func (r *{$this->modelName}RepositoryImpl) FindAll(ctx context.Context) ([]models.{$this->modelName}, error) {
	var result []models.{$this->modelName}
	err := r.db.WithContext(ctx).Find(&result).Error
	return result, err
}

func (r *{$this->modelName}RepositoryImpl) FindByID(ctx context.Context, id string) (models.{$this->modelName}, error) {
	var result models.{$this->modelName}
	err := r.db.WithContext(ctx).Where("id = ?", id).First(&result).Error
	if err != nil {
		if errors.Is(err, gorm.ErrRecordNotFound) {
			return result, errors.New("record not found")
		}
		return result, err
	}
	return result, nil
}

func (r *{$this->modelName}RepositoryImpl) Create(ctx context.Context, data *models.{$this->modelName}) error {
	return r.db.WithContext(ctx).Create(data).Error
}

func (r *{$this->modelName}RepositoryImpl) Update(ctx context.Context, data *models.{$this->modelName}) error {
	return r.db.WithContext(ctx).Save(data).Error
}

func (r *{$this->modelName}RepositoryImpl) Delete(ctx context.Context, id string) error {
	return r.db.WithContext(ctx).Where("id = ?", id).Delete(&models.{$this->modelName}{}).Error
}
GO;
    }

    protected function generateHandler(): string
    {
        $variableName = Str::camel($this->modelName);

        return <<<GO
package handlers

import (
	"net/http"
	"github.com/gin-gonic/gin"
	"your_project/models"
	"your_project/repository"
)

type {$this->modelName}Handler struct {
	repo repository.{$this->modelName}Repository
}

func New{$this->modelName}Handler(repo repository.{$this->modelName}Repository) *{$this->modelName}Handler {
	return &{$this->modelName}Handler{repo: repo}
}

func (h *{$this->modelName}Handler) Index(c *gin.Context) {
	result, err := h.repo.FindAll(c.Request.Context())
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
		return
	}
	c.JSON(http.StatusOK, result)
}

func (h *{$this->modelName}Handler) Show(c *gin.Context) {
	id := c.Param("id")
	result, err := h.repo.FindByID(c.Request.Context(), id)
	if err != nil {
		c.JSON(http.StatusNotFound, gin.H{"error": err.Error()})
		return
	}
	c.JSON(http.StatusOK, result)
}

func (h *{$this->modelName}Handler) Store(c *gin.Context) {
	var input models.{$this->modelName}
	if err := c.ShouldBindJSON(&input); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	err := h.repo.Create(c.Request.Context(), &input)
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
		return
	}
	c.JSON(http.StatusCreated, input)
}

func (h *{$this->modelName}Handler) Update(c *gin.Context) {
	id := c.Param("id")
	
	// Check if exists
	existing, err := h.repo.FindByID(c.Request.Context(), id)
	if err != nil {
		c.JSON(http.StatusNotFound, gin.H{"error": err.Error()})
		return
	}

	if err := c.ShouldBindJSON(&existing); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	err = h.repo.Update(c.Request.Context(), &existing)
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
		return
	}
	c.JSON(http.StatusOK, existing)
}

func (h *{$this->modelName}Handler) Destroy(c *gin.Context) {
	id := c.Param("id")
	err := h.repo.Delete(c.Request.Context(), id)
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
		return
	}
	c.JSON(http.StatusNoContent, nil)
}
GO;
    }

    protected function generateRoutes(): string
    {
        $routeName = Str::kebab(Str::plural($this->modelName));
        $handlerVar = Str::camel($this->modelName) . "Handler";

        return <<<GO
// In your router setup function:
// db := ... // initialize gorm.DB
// {$handlerVar} := handlers.New{$this->modelName}Handler(repository.New{$this->modelName}Repository(db))

r.GET("/{$routeName}", {$handlerVar}.Index)
r.GET("/{$routeName}/:id", {$handlerVar}.Show)
r.POST("/{$routeName}", {$handlerVar}.Store)
r.PUT("/{$routeName}/:id", {$handlerVar}.Update)
r.DELETE("/{$routeName}/:id", {$handlerVar}.Destroy)
GO;
    }
}
