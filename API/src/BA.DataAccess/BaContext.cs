namespace BA.DataAccess
{
    using Microsoft.EntityFrameworkCore;

    using BA.Domain.Division.Model;

    public class BaContext : DbContext
    {
        public DbSet<Division> Divisions { get; set; }

        protected override void OnConfiguring(DbContextOptionsBuilder optionsBuilder)
        {
            optionsBuilder.UseSqlServer(@"Server=localhost;Database=brandaddition;User Id=sa;Password=abc1234!Password;");
        }

        protected override void OnModelCreating(ModelBuilder modelBuilder)
        {
            modelBuilder.Entity<Division>().Property(x => x.DivisionId).HasColumnName("division_id");
            modelBuilder.Entity<Division>().Property(x => x.Name).HasColumnName("name");
            modelBuilder.Entity<Division>().ToTable("Division");
        }
    }
}