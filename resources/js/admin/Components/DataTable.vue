<template>
  <div class="data-table-container">
    <!-- Search and filters -->
    <div class="data-table-header mb-4 flex flex-wrap items-center justify-between">
      <div class="search-filter flex items-center space-x-2">
        <el-input
          v-model="searchQuery"
          placeholder="Search..."
          prefix-icon="el-icon-search"
          clearable
          @input="handleSearch"
        />
        
        <el-select
          v-if="filters.length > 0"
          v-model="activeFilters"
          multiple
          collapse-tags
          placeholder="Filters"
          @change="applyFilters"
        >
          <el-option-group
            v-for="group in filters"
            :key="group.name"
            :label="group.name"
          >
            <el-option
              v-for="item in group.options"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-option-group>
        </el-select>
      </div>
      
      <div class="actions">
        <slot name="actions"></slot>
      </div>
    </div>
    
    <!-- Table -->
    <el-table
      :data="paginatedData"
      :border="border"
      :stripe="stripe"
      :row-class-name="rowClassName"
      @sort-change="handleSortChange"
      v-loading="loading"
      element-loading-text="Loading..."
      element-loading-spinner="el-icon-loading"
    >
      <slot></slot>
      
      <!-- Empty data slot -->
      <template #empty>
        <div class="empty-data">
          <slot name="empty">
            <p>No data available</p>
          </slot>
        </div>
      </template>
    </el-table>
    
    <!-- Pagination -->
    <div class="data-table-footer mt-4 flex justify-between items-center">
      <div class="pagination-info text-sm text-gray-500">
        Showing {{ paginationInfo.from }} to {{ paginationInfo.to }} of {{ totalItems }} entries
      </div>
      
      <el-pagination
        :current-page.sync="currentPage"
        :page-size="pageSize"
        :page-sizes="[10, 20, 50, 100]"
        :total="totalItems"
        layout="sizes, prev, pager, next"
        @size-change="handleSizeChange"
        @current-change="handleCurrentChange"
      />
    </div>
  </div>
</template>

<script>
export default {
  name: 'DataTable',
  
  props: {
    data: {
      type: Array,
      required: true
    },
    loading: {
      type: Boolean,
      default: false
    },
    filters: {
      type: Array,
      default: () => []
    },
    border: {
      type: Boolean,
      default: true
    },
    stripe: {
      type: Boolean,
      default: true
    },
    rowClassName: {
      type: [String, Function],
      default: ''
    }
  },
  
  data() {
    return {
      searchQuery: '',
      activeFilters: [],
      currentPage: 1,
      pageSize: 10,
      sortBy: null,
      sortOrder: null,
      filteredData: []
    };
  },
  
  computed: {
    totalItems() {
      return this.filteredData.length;
    },
    
    paginatedData() {
      const start = (this.currentPage - 1) * this.pageSize;
      const end = start + this.pageSize;
      return this.filteredData.slice(start, end);
    },
    
    paginationInfo() {
      const from = this.totalItems === 0 ? 0 : (this.currentPage - 1) * this.pageSize + 1;
      const to = Math.min(from + this.pageSize - 1, this.totalItems);
      
      return {
        from,
        to
      };
    }
  },
  
  watch: {
    data: {
      immediate: true,
      handler() {
        this.applyFiltersAndSearch();
      }
    }
  },
  
  methods: {
    handleSearch() {
      this.currentPage = 1;
      this.applyFiltersAndSearch();
    },
    
    applyFilters() {
      this.currentPage = 1;
      this.applyFiltersAndSearch();
    },
    
    applyFiltersAndSearch() {
      let result = [...this.data];
      
      // Apply search
      if (this.searchQuery) {
        const query = this.searchQuery.toLowerCase();
        result = result.filter(item => {
          return Object.values(item).some(val => {
            if (val === null || val === undefined) return false;
            return String(val).toLowerCase().includes(query);
          });
        });
      }
      
      // Apply filters
      if (this.activeFilters.length > 0) {
        result = result.filter(item => {
          return this.activeFilters.some(filter => {
            const [field, value] = filter.split(':');
            return item[field] == value; // Use == for type coercion
          });
        });
      }
      
      // Apply sorting
      if (this.sortBy) {
        result.sort((a, b) => {
          const aVal = a[this.sortBy];
          const bVal = b[this.sortBy];
          
          if (aVal === bVal) return 0;
          
          const result = aVal > bVal ? 1 : -1;
          return this.sortOrder === 'ascending' ? result : -result;
        });
      }
      
      this.filteredData = result;
    },
    
    handleSortChange({ prop, order }) {
      this.sortBy = prop;
      this.sortOrder = order;
      this.applyFiltersAndSearch();
    },
    
    handleSizeChange(size) {
      this.pageSize = size;
    },
    
    handleCurrentChange(page) {
      this.currentPage = page;
    },
    
    refresh() {
      this.applyFiltersAndSearch();
    },
    
    resetFilters() {
      this.searchQuery = '';
      this.activeFilters = [];
      this.currentPage = 1;
      this.applyFiltersAndSearch();
    }
  }
};
</script>

<style scoped>
.data-table-container {
  width: 100%;
}

.empty-data {
  padding: 2rem;
  text-align: center;
  color: #909399;
}
</style>
